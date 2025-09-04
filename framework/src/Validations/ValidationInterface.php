<?php

declare(strict_types=1);

namespace Framework\Validations;

interface ValidationInterface
{
    /**
     * @return array<array-key, string>
     */
    public function messages(): array;

    public function valid(): bool;

    public function invalid(): bool;
}
