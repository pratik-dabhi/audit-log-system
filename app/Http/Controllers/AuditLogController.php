<?php

namespace App\Http\Controllers;

use App\Http\Repositories\AuditLog\AuditLogRepository;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct(private AuditLogRepository $auditLogRepository) {}

    public function index()
    { 
        $logs = $this->auditLogRepository->get('user');
        return view('audit-logs.index', compact('logs')); 
    }
}
