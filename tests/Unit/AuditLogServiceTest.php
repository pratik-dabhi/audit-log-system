<?php

namespace Tests\Unit;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_sensitive_fields_are_hidden()
    {
        $service = new AuditLogService;

        $user = User::factory()->create([
            'password' => 'secret123',
        ]);

        $user->password = 'newsecret';
        $user->save();

        $service->log($user, 'updated');

        $log = AuditLog::latest()->first();

        $this->assertEquals('********', $log->old_values['password']);
        $this->assertEquals('********', $log->new_values['password']);
    }
}
