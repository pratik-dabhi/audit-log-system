<?php

namespace App\Observers;

use App\Http\Repositories\AuditLog\AuditLogRepository;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{

    public function __construct(protected AuditLogRepository $auditLogRepository) {}

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $user = Auth::user();

        $data = [
            'user_id' => $user->id,
            'event' => 'order.create',
            'auditable_type' => Order::class,
            'auditable_id' => $order->id,
            'before' => null,
            'after' => [
                "name" => $user->name,
                "amount" => $order->amount,
                "status" => $order->status
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        $this->auditLogRepository->create($data);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updating(Order $order): void
    {
        $user = Auth::user();
        $before = [];
        $after = [];

        if($order->isDirty('amount')){
            $before['amount'] = $order->getOriginal('amount'); 
            $after['amount'] = $order->amount; 
        }

        if($order->isDirty('status')){
            $before['status'] = $order->getOriginal('status'); 
            $after['status'] = $order->status; 
        }
        
        $data = [
            'user_id' => $user->id,
            'event' => 'order.update',
            'auditable_type' => Order::class,
            'auditable_id' => $order->id,
            'before' => $before,
            'after' => $after,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        $this->auditLogRepository->create($data);
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        $user = Auth::user();
        $data = [
            'user_id' => $user->id,
            'event' => 'order.deleted',
            'auditable_type' => Order::class,
            'auditable_id' => $order->id,
            'after' => null,
            'before' => [
                "name" => $user->name,
                "amount" => $order->amount,
                "status" => $order->status
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        $this->auditLogRepository->create($data);
    }
}
