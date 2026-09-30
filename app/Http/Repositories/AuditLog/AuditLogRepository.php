<?php

namespace App\Http\Repositories\AuditLog;

use App\Models\AuditLog;

class AuditLogRepository
{
    public function __construct(protected AuditLog $model){}

    public function get($with = [])
    {
        return $this->model->with($with)->latest()->get();
    }

    public function create(mixed $data)
    {
        return $this->model->create($data);
    }

}
