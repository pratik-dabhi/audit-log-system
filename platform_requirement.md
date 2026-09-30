# Laravel Practical Assignment: Audit Log System

## Time Limit

**2.5–3 hours**

## Objective

Build a Laravel-based Audit Log System that records:

> Who did what, to which record, when, and what changed.

You will build a simple Order API and implement automatic auditing for Order creation, updates, and deletion.

The audit functionality should be implemented in a reusable and maintainable way.

## Functional Requirements

### 1. Order Management

Create an `orders` table containing at least:

* `id`
* `customer_name`
* `amount`
* `status`
* `created_at`
* `updated_at`

Supported statuses:

* `pending`
* `paid`
* `cancelled`

Implement APIs to:

```text
POST   /api/orders
GET    /api/orders/{order}
PATCH  /api/orders/{order}
DELETE /api/orders/{order}
```

Validate all incoming data.

### 2. Audit Logs

Create an `audit_logs` table containing:

```text
id
user_id
event
auditable_type
auditable_id
old_values
new_values
ip_address
user_agent
created_at
```

The system must automatically create audit records for:

```text
created
updated
deleted
```

### 3. Update Tracking

When an Order is updated, only changed attributes should be recorded.

For example:

```text
Before:
status = pending
amount = 5000

After:
status = paid
amount = 5000
```

The audit record should contain only:

```json
{
    "old_values": {
        "status": "pending"
    },
    "new_values": {
        "status": "paid"
    }
}
```

### 4. Architecture

Do not place audit logic directly inside controllers.

Use Laravel's model lifecycle capabilities.

A suggested structure is:

```text
Order
  ↓
OrderObserver
  ↓
AuditLogService
  ↓
AuditLog
  ↓
audit_logs
```

You may use a different architecture if you can justify it.

### 5. Sensitive Data

The audit system must not store sensitive values such as:

```text
password
password_confirmation
token
access_token
refresh_token
api_token
secret
credit_card
card_number
cvv
```

Design the implementation so additional excluded fields can easily be added later.

### 6. Audit APIs

Implement:

```text
GET /api/audit-logs
GET /api/orders/{order}/audit-logs
```

The main audit endpoint must support pagination.

Implement at least two useful filters, such as:

```text
event
user_id
auditable_type
auditable_id
date_from
date_to
```

### 7. Authorization

Audit logs contain sensitive information.

Only authorized users should be able to access audit APIs.

Explain your authorization approach in the README.

### 8. Relationships

Use appropriate Laravel relationships.

The audit log should be able to identify:

```text
User
Auditable model
```

A polymorphic relationship is recommended for the auditable model.

### 9. Database

Add appropriate indexes for common audit queries.

Be prepared to explain your indexing decisions.

### 10. Tests

Write automated tests covering at least:

* Creating an order creates an audit record.
* Updating an order creates an audit record.
* Only changed fields are recorded.
* Deleting an order creates an audit record.
* Sensitive fields are excluded.
* Unauthorized users cannot access audit APIs.
* Audit APIs are paginated.

## Deliverables

Submit:

1. Git repository
2. Working Laravel application
3. Database migrations
4. Automated tests
5. README
6. Optional Postman collection

## README Requirements

Document:

```text
Installation
Environment setup
Database setup
API documentation
Architecture
Audit flow
Security decisions
Testing
Database indexes
Known limitations
Potential future improvements
```

## Important Discussion Topics

During the technical discussion, be prepared to explain:

1. Why did you choose an Observer?
2. Why did you separate audit creation into a service?
3. How did you detect changed attributes?
4. How does Laravel behave with mass updates?
5. Why did you choose your database indexes?
6. How would you handle 100+ million audit records?
7. Should audit logs be editable or deletable?
8. What happens if audit creation fails?
9. Should auditing happen synchronously or through a queue?
10. How would you make the auditing system reusable for Product, User, Invoice, and other models?

## Evaluation

The implementation will be evaluated on:

* Laravel fundamentals
* Architecture
* Eloquent knowledge
* Change tracking
* API design
* Security
* Testing
* Database design
* Code quality
* Ability to explain engineering decisions

The goal is not to build a large application. Focus on producing a **clean, maintainable, secure Laravel implementation within the time limit**.
