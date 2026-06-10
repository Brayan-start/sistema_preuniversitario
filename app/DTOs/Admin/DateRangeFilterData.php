<?php

namespace App\DTOs\Admin;

use Carbon\Carbon;

class DateRangeFilterData
{
    public function __construct(
        public readonly ?string $from = null,
        public readonly ?string $to = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['fecha_desde'] ?? $data['from'] ?? null,
            $data['fecha_hasta'] ?? $data['to'] ?? null,
        );
    }

    public function startDate(): ?Carbon
    {
        return $this->from ? Carbon::parse($this->from)->startOfDay() : null;
    }

    public function endDate(): ?Carbon
    {
        return $this->to ? Carbon::parse($this->to)->endOfDay() : null;
    }
}
