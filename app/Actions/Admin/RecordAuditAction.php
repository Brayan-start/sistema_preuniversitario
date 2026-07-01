<?php

namespace App\Actions\Admin;

use App\Services\Admin\AuditService;

class RecordAuditAction
{
    public function __construct(private readonly AuditService $auditService) {}

    public function execute(int $userId, string $accion, string $modulo, string $descripcion, ?string $ipAddress = null)
    {
        return $this->auditService->record($userId, $accion, $modulo, $descripcion, $ipAddress);
    }
}
