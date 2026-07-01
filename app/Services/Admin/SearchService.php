<?php

namespace App\Services\Admin;

use App\Repositories\Admin\AspiranteRepository;

class SearchService
{
    public function __construct(private readonly AspiranteRepository $repository) {}

    public function availableFields(): array
    {
        return $this->repository->fields();
    }

    public function availableOperators(): array
    {
        return ['equals', 'contains', 'starts_with', 'ends_with'];
    }

    public function search(array $filters = []): array
    {
        return [
            'items' => $this->repository->search($filters),
            'fields' => $this->availableFields(),
            'operators' => $this->availableOperators(),
        ];
    }

    public function list(array $filters = []): array
    {
        return [
            'items' => $this->repository->paginate($filters),
            'fields' => $this->availableFields(),
        ];
    }
}
