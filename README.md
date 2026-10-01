# Audit Log System

## Overview
A Laravel-based Audit Log System that records who did what, to which record, when, and what changed.

## Installation & Setup
1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your database
4. Run `php artisan key:generate`
5. Run `php artisan migrate`
6. Run `php artisan serve`

## API Documentation

### Order Management
- `POST /api/orders` - Create a new order
- `GET /api/orders/{order}` - Retrieve an order
- `PATCH /api/orders/{order}` - Update an order (partial updates allowed)
- `DELETE /api/orders/{order}` - Delete an order

### Audit Logs
- `GET /api/audit-logs` - Retrieve all audit logs (Paginated). Supports filters: `event`, `user_id`, `auditable_type`, `auditable_id`, `date_from`, `date_to`.
- `GET /api/orders/{order}/audit-logs` - Retrieve audit logs for a specific order

## Architecture
- **Pattern:** `Model -> Observer -> Service -> Model`
- **OrderObserver:** Hooks into the Eloquent lifecycle events (`created`, `updated`, `deleted`) to trigger the logging process.
- **AuditLogService:** A generic service responsible for identifying changed attributes (`getChanges()`), masking sensitive fields, and persisting the audit record.

## Security Decisions
- **Authentication:** The API endpoints are protected using Laravel Sanctum (`auth:sanctum` middleware) ensuring only authenticated users can access or generate logs.
- **Data Masking:** Sensitive data such as passwords, tokens, and credit cards are scrubbed and replaced with `********` before being saved to the database.

## Database Indexes
Indexes have been added to the `audit_logs` table for:
- `user_id` (frequent filtering by user)
- `event` (frequent filtering by action type)
- `auditable_type` & `auditable_id` (polymorphic index for fast relation lookups)

## Testing
The application includes comprehensive Feature and Unit tests (`php artisan test`) verifying core logic such as sensitive field masking, dirty attribute detection, API pagination, and authorization.
