<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::latest()->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'status' => 'nullable|string|in:pending,paid,cancelled',
        ]);

        $order = DB::transaction(function () use ($validated) {
            return Order::create($validated);
        });

        return response()->json($order, Response::HTTP_CREATED);
    }

    public function show(Order $order)
    {
        return response()->json($order);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric',
            'status' => 'sometimes|required|string|in:pending,paid,cancelled',
        ]);

        DB::transaction(function () use ($order, $validated) {
            $order->update($validated);
        });

        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->delete();
        });

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
