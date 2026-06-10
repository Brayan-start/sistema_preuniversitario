<?php

namespace App\Repositories\Admin;

use App\DTOs\Admin\DateRangeFilterData;
use App\Models\Auditoria;
use App\Models\User;

class AuditRepository
{
    public function paginate(array $filters = [], int $perPage = 30)
    {
        $query = Auditoria::with('user')->latest();

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['accion'])) {
            $query->where('accion', 'like', '%' . $filters['accion'] . '%');
        }

        $range = DateRangeFilterData::fromArray($filters);
        if ($range->startDate() && $range->endDate()) {
            $query->whereBetween('created_at', [$range->startDate(), $range->endDate()]);
        } elseif ($range->startDate()) {
            $query->where('created_at', '>=', $range->startDate());
        } elseif ($range->endDate()) {
            $query->where('created_at', '<=', $range->endDate());
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function users(): array
    {
        return User::orderBy('name')->pluck('name', 'id')->all();
    }

    public function record(int $userId, string $accion, string $modulo, string $descripcion, ?string $ipAddress = null): Auditoria
    {
        $ipSegment = $ipAddress ? " | IP: {$ipAddress}" : '';

        return Auditoria::create([
            'user_id' => $userId,
            'accion' => $accion,
            'descripcion' => "[{$modulo}]{$ipSegment} {$descripcion}",
        ]);
    }
}
