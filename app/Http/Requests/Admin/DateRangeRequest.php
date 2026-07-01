<?php

namespace App\Http\Requests\Admin;

use App\DTOs\Admin\DateRangeFilterData;
use Illuminate\Foundation\Http\FormRequest;

class DateRangeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_desde' => ['nullable', 'date'],
            'fecha_hasta' => ['nullable', 'date', 'after_or_equal:fecha_desde'],
        ];
    }

    public function toFilterData(): DateRangeFilterData
    {
        return DateRangeFilterData::fromArray($this->validated());
    }
}
