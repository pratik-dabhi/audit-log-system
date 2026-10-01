<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"; window.axios.defaults.withCredentials = true;</script>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f5f7fb; color: #1f2937; }
        .container { max-width: 650px; margin: 50px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); }
        h1 { margin-top: 0; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; }
        input, select { width: 100%; padding: 11px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
        input:focus, select:focus { outline: none; border-color: #2563eb; }
        .error { color: #dc2626; font-size: 13px; margin-top: 5px; }
        .buttons { display: flex; gap: 10px; margin-top: 25px; }
        .btn { padding: 11px 18px; border-radius: 6px; border: none; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-secondary { background: #e5e7eb; color: #374151; }
    </style>
</head>
<body>
    @include('layouts.header')
<div class="container">
    <div class="card" x-data="createOrderApp()">
        <h1>Create Order</h1>
        <form @submit.prevent="submitForm">
            
            <div class="form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" x-model="form.customer_name" id="customer_name" placeholder="Enter customer name">
                <template x-if="errors.customer_name">
                    <div class="error" x-text="errors.customer_name[0]"></div>
                </template>
            </div>

            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" x-model="form.amount" id="amount" step="0.01" min="0" placeholder="Enter order amount">
                <template x-if="errors.amount">
                    <div class="error" x-text="errors.amount[0]"></div>
                </template>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select x-model="form.status" id="status">
                    <option value="">Select status</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <template x-if="errors.status">
                    <div class="error" x-text="errors.status[0]"></div>
                </template>
            </div>

            <div class="buttons">
                <button type="submit" class="btn btn-primary" :disabled="loading" x-text="loading ? 'Creating...' : 'Create Order'"></button>
                <a href="/orders" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<script>
    function createOrderApp() {
        return {
            form: {
                customer_name: '',
                amount: '',
                status: 'pending'
            },
            errors: {},
            loading: false,
            submitForm() {
                this.loading = true;
                this.errors = {};
                axios.post('{{ url('/api/orders') }}', this.form)
                    .then(response => {
                        window.location.href = '/orders?success=Order+created+successfully.';
                    })
                    .catch(error => {
                        this.loading = false;
                        if(error.response && error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        } else {
                            alert('An error occurred');
                        }
                    });
            }
        }
    }
</script>
</body>
</html>