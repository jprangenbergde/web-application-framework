<?php

declare(strict_types=1);

namespace Framework\Validations\Validators;

use Framework\Interfaces\ToArrayInterface;
use Framework\Validations\Validation;
use Framework\Validations\ValidationInterface;
use RuntimeException;
use Override;

readonly class MapValidator implements ValidatorInterface
{
    /**
     * @param array<string, ValidatorInterface> $configuration
     */
    public function __construct(
        private array $configuration
    ) {}

    #[Override]
    public function validate(mixed $data): ValidationInterface
    {
        if ($data instanceof ToArrayInterface) {
            $data = $data->toArray();
        }

        if (!is_array($data) || array_is_list(array_flip(array_keys($data)))) {
            throw new RuntimeException(
                sprintf(
                    '%s requires an array map; `%s` given',
                    self::class,
                    get_debug_type($data)
                )
            );
        }

        $messages = [];

        foreach ($this->configuration as $name => $validator) {
            $validation = $validator->validate($data[$name] ?? null);
            if ($validation->valid()) {
                continue;
            }

            $messages[$name] = $this->modifyMessages(
                $name,
                $validation->messages()
            );
        }

        if ($messages !== []) {
            return Validation::errors($messages);
        }

        return Validation::ok();
    }

    private function modifyMessages(string $name, array $messages): array
    {
        foreach ($messages as &$message) {
            $message = str_replace(':value', ucfirst($name), $message);
        }

        return $messages;
    }
}
