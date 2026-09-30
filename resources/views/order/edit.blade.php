<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Order #{{ $order->id }}</title>

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
            max-width: 650px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 25px;
        }

        .order-id {
            color: #6b7280;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            padding: 11px 18px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }
    </style>
</head>

<body>
@include('layouts.header')

<div class="container">

    <div class="card">

        <h1>Edit Order</h1>

        <div class="order-id">
            Order #{{ $order->id }}
        </div>

        <form
            action="{{ route('orders.update', $order) }}"
            method="POST"
        >

            @csrf

            @method('PUT')

            {{-- Amount --}}
            <div class="form-group">

                <label for="amount">
                    Amount
                </label>

                <input
                    type="number"
                    name="amount"
                    id="amount"
                    value="{{ old('amount', $order->amount) }}"
                    step="0.01"
                    min="0"
                    placeholder="Enter order amount"
                >

                @error('amount')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Status --}}
            <div class="form-group">

                <label for="status">
                    Status
                </label>

                <select name="status" id="status">

                    <option value="pending"
                        {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="processing"
                        {{ old('status', $order->status) === 'processing' ? 'selected' : '' }}>
                        Processing
                    </option>

                    <option value="completed"
                        {{ old('status', $order->status) === 'completed' ? 'selected' : '' }}>
                        Completed
                    </option>

                    <option value="cancelled"
                        {{ old('status', $order->status) === 'cancelled' ? 'selected' : '' }}>
                        Cancelled
                    </option>

                </select>

                @error('status')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="buttons">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Order
                </button>

                <a
                    href="{{ route('orders.index') }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>