<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    protected array $hiddenFields = [
        'password',
        'password_confirmation',
        'token',
        'access_token',
        'refresh_token',
        'api_token',
        'secret',
        'credit_card',
        'card_number',
        'cvv',
    ];

    public function log(Model $model, string $event): void
    {
        $oldValues = [];
        $newValues = [];

        if ($event === 'created') {
            $newValues = $model->getAttributes();
        } elseif ($event === 'updated') {
            $newValues = $model->getChanges();
            $oldValues = Arr::only($model->getOriginal(), array_keys($newValues));
        } elseif ($event === 'deleted') {
            $oldValues = $model->getAttributes();
        }

        $oldValues = $this->hideSensitiveData($oldValues);
        $newValues = $this->hideSensitiveData($newValues);

        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => $model->getMorphClass(),
            'auditable_id' => $model->getKey(),
            'old_values' => empty($oldValues) ? null : $oldValues,
            'new_values' => empty($newValues) ? null : $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    protected function hideSensitiveData(array $attributes): array
    {
        foreach ($this->hiddenFields as $field) {
            if (array_key_exists($field, $attributes)) {
                $attributes[$field] = '********';
            }
        }

        return $attributes;
    }
}
