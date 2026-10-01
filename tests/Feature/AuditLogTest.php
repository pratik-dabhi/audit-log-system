<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_creating_order_creates_audit_record()
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->postJson('/api/orders', [
            'customer_name' => 'John Doe',
            'amount' => 1000,
            'status' => 'pending',
        ]);

        $response->assertCreated();

        $orderId = $response->json('id');

        $this->assertDatabaseHas('audit_logs', [
            'event' => 'created',
            'auditable_type' => Order::class,
            'auditable_id' => $orderId,
            'user_id' => $this->user->id,
        ]);

        $log = AuditLog::first();
        $this->assertNotNull($log->new_values);
        $this->assertEquals('John Doe', $log->new_values['customer_name']);
    }

    public function test_updating_order_creates_audit_record_with_only_changed_fields()
    {
        $this->actingAs($this->user, 'sanctum');

        $order = Order::create([
            'customer_name' => 'John Doe',
            'amount' => 1000,
            'status' => 'pending',
        ]);

        AuditLog::truncate();

        $response = $this->patchJson("/api/orders/{$order->id}", [
            'status' => 'paid',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('audit_logs', [
            'event' => 'updated',
            'auditable_id' => $order->id,
        ]);

        $log = AuditLog::first();
        $this->assertEquals(['status' => 'pending'], $log->old_values);
        $this->assertEquals(['status' => 'paid'], $log->new_values);
        $this->assertArrayNotHasKey('amount', $log->new_values);
    }

    public function test_deleting_order_creates_audit_record()
    {
        $this->actingAs($this->user, 'sanctum');

        $order = Order::create([
            'customer_name' => 'John Doe',
            'amount' => 1000,
            'status' => 'pending',
        ]);

        AuditLog::truncate();

        $response = $this->deleteJson("/api/orders/{$order->id}");

        $response->assertNoContent();

        $this->assertDatabaseHas('audit_logs', [
            'event' => 'deleted',
            'auditable_id' => $order->id,
        ]);

        $log = AuditLog::first();
        $this->assertEquals('John Doe', $log->old_values['customer_name']);
        $this->assertNull($log->new_values);
    }

    public function test_unauthorized_users_cannot_access_audit_apis()
    {
        $response = $this->getJson('/api/audit-logs');
        $response->assertUnauthorized();
    }

    public function test_audit_apis_are_paginated()
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->getJson('/api/audit-logs');
        $response->assertOk();
        $response->assertJsonStructure([
            'data', 'current_page', 'last_page', 'per_page', 'total',
        ]);
    }
}
