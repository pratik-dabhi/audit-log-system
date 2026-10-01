<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"; window.axios.defaults.withCredentials = true;</script>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f5f7fb; color: #1f2937; }
        .container { max-width: 1100px; margin: 50px auto; padding: 0 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        h1 { margin: 0; }
        .btn { display: inline-block; padding: 10px 16px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-edit { background: #f59e0b; color: white; }
        .btn-delete { background: #dc2626; color: white; }
        .table-wrapper { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f9fafb; font-weight: 600; }
        tr:last-child td { border-bottom: none; }
        .status { display: inline-block; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-paid { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .actions { display: flex; gap: 8px; }
        .empty { text-align: center; padding: 40px; color: #6b7280; }
        .success { background: #dcfce7; color: #166534; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; }
    </style>
</head>
<body>
@include('layouts.header')
<div class="container" x-data="ordersApp()" x-init="fetchOrders()">
    <div class="header">
        <h1>Orders</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">+ Create Order</a>
    </div>

    @if(request()->has('success'))
        <div class="success">
            {{ request()->success }}
        </div>
    @endif

    <div class="table-wrapper">
        <template x-if="loading">
            <div class="empty">Loading...</div>
        </template>
        <template x-if="!loading && orders.length > 0">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer Name</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="order in orders" :key="order.id">
                        <tr>
                            <td x-text="'#' + order.id"></td>
                            <td x-text="order.customer_name"></td>
                            <td x-text="'₹' + parseFloat(order.amount).toFixed(2)"></td>
                            <td>
                                <span :class="'status status-' + order.status" x-text="order.status"></span>
                            </td>
                            <td x-text="new Date(order.created_at).toLocaleDateString()"></td>
                            <td>
                                <div class="actions">
                                    <a :href="'{{ url('/orders') }}/' + order.id + '/edit'" class="btn btn-edit">Edit</a>
                                    <button @click="deleteOrder(order.id)" class="btn btn-delete">Delete</button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </template>
        <template x-if="!loading && orders.length === 0">
            <div class="empty">
                <h3>No orders found</h3>
                <p>Create your first order to get started.</p>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">Create Order</a>
            </div>
        </template>
    </div>
</div>
<script>
    function ordersApp() {
        return {
            orders: [],
            loading: true,
            fetchOrders() {
                axios.get('{{ url('/api/orders') }}')
                    .then(response => {
                        this.orders = response.data.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching orders:', error);
                        this.loading = false;
                    });
            },
            deleteOrder(id) {
                if(confirm('Are you sure you want to delete this order?')) {
                    axios.delete('{{ url('/api/orders') }}/' + id)
                        .then(() => {
                            window.location.href = '/orders?success=Order+deleted+successfully.';
                        })
                        .catch(error => {
                            console.error('Error deleting order:', error);
                        });
                }
            }
        }
    }
</script>
</body>
</html>