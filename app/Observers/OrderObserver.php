<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\AuditLogService;

class OrderObserver
{
    public function __construct(protected AuditLogService $auditLogService) {}

    public function created(Order $order): void
    {
        $this->auditLogService->log($order, 'created');
    }

    public function updated(Order $order): void
    {
        $this->auditLogService->log($order, 'updated');
    }

    public function deleted(Order $order): void
    {
        $this->auditLogService->log($order, 'deleted');
    }
}
