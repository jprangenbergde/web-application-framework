<?php

declare(strict_types=1);

namespace Framework\Databases;

use Framework\Models\ModelInterface;
use PDO;
use PDOStatement;

final readonly class DatabaseManager
{
    public function __construct(private PDO $connection) {}

    /**
     * @template T of ModelInterface
     *
     * @param class-string<T> $class
     *
     * @return T|null
     */
    public function select(string $class, array $filters = [], array $orderBy = []): ?ModelInterface
    {
        $result = $this->selectAll($class, $filters, $orderBy, 1)[0] ?? null;

        if (!$result instanceof ModelInterface) {
            return null;
        }

        return $result;
    }

    /**
     * @template T of ModelInterface
     *
     * @param class-string<T> $class
     *
     * @return array<T>
     */
    public function selectAll(string $class, array $filters = [], array $orderBy = [], ?int $limit = null): array
    {
        $statement = $this->executeSelectStatement($class, $filters, $orderBy, $limit);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$row) {
            foreach ($row as &$column) {
                if (is_string($column) && json_validate($column)) {
                    $column = json_decode($column, true);
                }
            }
        }

        return maps_to_objects($rows, $class);
    }

    private function update(ModelInterface $model): void
    {
        $table = $model::getTableName();

        $data = $model->toArray();
        unset($data[$model::getIdColumnName()]);

        $updateStatement = '';
        foreach ($data as $key => $value) {
            $updateStatement .= "`{$key}` = ?, ";
        }
        $updateStatement = rtrim($updateStatement, ', ');

        foreach ($data as &$value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
        }

        $statement = $this->connection->prepare(
            "UPDATE $table SET $updateStatement WHERE `{$model::getIdColumnName()}` = {$model->getId()}"
        );
        $statement->execute(array_values($data));
    }

    private function insert(ModelInterface $model): void
    {
        $table = $model::getTableName();

        $data = $model->toArray();
        unset($data[$model::getIdColumnName()]);

        $keys = '`' . implode('` , `', array_keys($data)) . '`';
        $values = implode(' , ', array_fill(0, count($data), '?'));

        foreach ($data as &$value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
        }

        $statement = $this->connection->prepare("INSERT INTO {$table} ({$keys}) VALUES ({$values})");
        $statement->execute(array_values($data));

        $model->{$model::getIdColumnName()} = (int) $this->connection->lastInsertId();
    }

    public function save(ModelInterface $model): void
    {
        if ($model->getId() > 0) {
            $this->update($model);
        } else {
            $this->insert($model);
        }
    }

    public function delete(ModelInterface $model): void
    {
        $table = $model::getTableName();

        $statement = $this->connection->prepare("DELETE FROM `$table` WHERE `{$model::getIdColumnName()}` = ?");
        $statement->execute([$model->getId()]);
    }

    /**
     * @param array<ModelInterface> $models
     */
    public function deleteMany(array $models): void
    {
        $idsGroupedByClass = [];
        foreach ($models as $model) {
            $idsGroupedByClass[$model::class][] = $model->getId();
        }

        /** @var class-string<ModelInterface> $class */
        foreach ($idsGroupedByClass as $class => $ids) {
            $table = $class::getTableName();
            $ids = implode(',', $ids);

            $this->connection->query("DELETE FROM `$table` WHERE `{$model::getIdColumnName()}` IN ($ids)");
        }
    }

    /**
     * @template T of ModelInterface
     *
     * @param class-string<T> $class
     */
    private function executeSelectStatement(
        string $class,
        array $filters,
        array $orderBy,
        ?int $limit = null
    ): PDOStatement|false {
        [$values, $query] = $this->createSelectStatement($class, $filters, $orderBy, $limit);

        $statement = $this->connection->prepare($query);
        $statement->execute($values);

        return $statement;
    }

    /**
     * @template T of ModelInterface
     *
     * @param class-string<T> $class
     */
    private function createSelectStatement(string $class, array $filters, array $orderBy, ?int $limit): array
    {
        $table = $class::getTableName();
        $whereStatement = $this->createWhereStatement($filters);

        unset($filters['connectOperator']);
        $values = array_column_of_list_or_map($filters, 'value');

        $query = "SELECT * FROM $table WHERE $whereStatement";

        if ($orderBy !== []) {
            $orderByStatement = $this->createOrderByStatement($orderBy);
            $query .= " ORDER BY $orderByStatement";
        }

        if ($limit !== null) {
            $query .= " LIMIT $limit";
        }

        return [$values, $query];
    }

    private function createWhereStatement(array $filters): string
    {
        if ($filters === []) {
            return '1';
        }

        if (!array_is_list($filters)) {
            return $this->getFilterGroupStatement($filters);
        }

        $filterGroupStatements = [];
        foreach ($filters as $filter) {
            $filterGroupStatements[] = $this->getFilterGroupStatement($filter);
        }

        return '(' . implode(') AND (', $filterGroupStatements) . ')';
    }

    public function createOrderByStatement(array $orderBy): string
    {
        $orderByStatement = '';

        foreach ($orderBy as $orderByColumn) {
            [$column, $direction] = $orderByColumn;
            $orderByStatement .= $column . ' ' . $direction . ', ';
        }

        return rtrim($orderByStatement, ', ');
    }

    private function getFilterGroupStatement(array $filters): string
    {
        $whereStatement = '';

        $connectOperator = $filters['connectOperator'] ?? 'AND';
        unset($filters['connectOperator']);

        foreach ($filters as $key => $filter) {
            $operator = '=';
            if (is_array($filter)) {
                $operator = $filter['operator'] ?? '=';
            }

            $whereStatement .= "`$key` $operator ? $connectOperator ";
        }

        return rtrim($whereStatement, "$connectOperator ");
    }
}
