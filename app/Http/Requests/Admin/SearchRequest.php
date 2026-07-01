<?php

namespace App\Http\Requests\Admin;

use App\DTOs\Admin\SearchFilterData;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'field' => ['nullable', 'string', 'max:100'],
            'operator' => ['nullable', 'in:equals,contains,starts_with,ends_with'],
            'value' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toFilterData(): SearchFilterData
    {
        return SearchFilterData::fromArray($this->validated());
    }
}
