<?php

namespace Framework\Models;

use Framework\Interfaces\ArrayableInterface;

interface ModelInterface extends ArrayableInterface
{
    public function getId(): int;

    public static function getIdColumnName(): string;

    public static function getTableName(): string;
}
