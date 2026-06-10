<?php

namespace App\Services\Admin;

use App\Repositories\Admin\AuditRepository;

class AuditService
{
    public function __construct(private readonly AuditRepository $repository) {}

    public function defaultFilters(): array
    {
        return [
            'fecha_desde' => now()->startOfMonth()->toDateString(),
            'fecha_hasta' => now()->endOfMonth()->toDateString(),
        ];
    }

    public function paginate(array $filters = [], int $perPage = 30)
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function record(int $userId, string $accion, string $modulo, string $descripcion, ?string $ipAddress = null)
    {
        return $this->repository->record($userId, $accion, $modulo, $descripcion, $ipAddress);
    }

    public function users(): array
    {
        return $this->repository->users();
    }
}
