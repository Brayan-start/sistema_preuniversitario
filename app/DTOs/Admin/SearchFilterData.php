<?php

namespace App\DTOs\Admin;

class SearchFilterData
{
    public function __construct(
        public readonly ?string $field = null,
        public readonly ?string $operator = null,
        public readonly ?string $value = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['field'] ?? null,
            $data['operator'] ?? null,
            $data['value'] ?? null,
        );
    }
}
