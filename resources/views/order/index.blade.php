<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Orders</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        h1 {
            margin: 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-edit {
            background: #f59e0b;
            color: white;
        }

        .btn-delete {
            background: #dc2626;
            color: white;
        }

        .table-wrapper {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f9fafb;
            font-weight: 600;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-processing {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
@include('layouts.header')
<div class="container">
    <div class="header">
        <h1>Orders</h1>

        <a href="{{ route('orders.create') }}" class="btn btn-primary">
            + Create Order
        </a>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-wrapper">

        @if($orders->count())

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($orders as $order)

                    <tr>

                        <td>
                            #{{ $order->id }}
                        </td>

                        <td>
                            ₹{{ number_format($order->amount, 2) }}
                        </td>

                        <td>

                            <span class="status status-{{ $order->status }}">
                                {{ $order->status }}
                            </span>

                        </td>

                        <td>
                            {{ $order->created_at->format('d M Y') }}
                        </td>

                        <td>

                            <div class="actions">

                                <a
                                    href="{{ route('orders.edit', $order) }}"
                                    class="btn btn-edit"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('orders.destroy', $order) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this order?');"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-delete"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @endforeach

                </tbody>
            </table>

        @else

            <div class="empty">
                <h3>No orders found</h3>
                <p>Create your first order to get started.</p>

                <a
                    href="{{ route('orders.create') }}"
                    class="btn btn-primary"
                >
                    Create Order
                </a>
            </div>

        @endif

    </div>

</div>

</body>
</html>