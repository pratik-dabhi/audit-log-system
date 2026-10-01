<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Order;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }
        if ($request->filled('auditable_id')) {
            $query->where('auditable_id', $request->auditable_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return response()->json($query->latest()->paginate(15));
    }

    public function orderLogs(Order $order)
    {
        $logs = $order->auditLogs()->with('user')->latest()->paginate(15);

        return response()->json($logs);
    }
}
