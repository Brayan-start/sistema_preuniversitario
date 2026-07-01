<?php

namespace App\Repositories\Admin;

use Illuminate\Database\Eloquent\Builder;
use App\DTOs\Admin\SearchFilterData;
use App\Models\Aspirante;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class AspiranteRepository
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->query($filters)->paginate($perPage)->withQueryString();
    }

    public function search(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->query($filters)->paginate($perPage)->withQueryString();
    }

    public function fields(): array
    {
        $columns = Schema::getColumnListing('aspirantes');

        return array_values(array_unique(array_merge($columns, ['user.email'])));
    }

    private function query(array $filters)
    {
        $search = SearchFilterData::fromArray($filters);
        $query = Aspirante::query()->with('user')->latest();

        if ($search->field && $search->value !== null && $search->value !== '') {
            $this->applyFilter($query, $search);
        }

        return $query;
    }

    private function applyFilter(Builder $query, SearchFilterData $search): void
    {
        $operator = match ($search->operator) {
            'equals' => '=',
            'starts_with', 'ends_with', 'contains' => 'like',
            default => 'like',
        };

        $value = match ($search->operator) {
            'starts_with' => $search->value . '%',
            'ends_with' => '%' . $search->value,
            'equals' => $search->value,
            default => '%' . $search->value . '%',
        };

        if ($search->field === 'user.email') {
            $query->whereHas('user', fn($userQuery) => $userQuery->where('email', $operator, $value));

            return;
        }

        if (in_array($search->field, Schema::getColumnListing('aspirantes'), true)) {
            $query->where($search->field, $operator, $value);
        }
    }
}
