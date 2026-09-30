<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Order\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(protected OrderRepository $orderRepository){}

    public function index()
    {
        $orders = $this->orderRepository->get();

        return view('order.index', compact('orders'));
    }

    public function create()
    {
        return view('order.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'amount' => ['required'],
                'status' => ['required', 'string'],
            ]);

            $validated['user_id'] = Auth::user()->id;
            $this->orderRepository->create($validated);
            DB::commit();
            return redirect()->intended('/order');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function edit(int $id)
    {
        $order = $this->orderRepository->getById($id);
        return view('order.edit' , compact('order'));
    }

    public function update(int $id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'amount' => ['required'],
                'status' => ['required', 'string'],
            ]);

            $validated['user_id'] = Auth::user()->id;
            $this->orderRepository->update($id, $validated);

            DB::commit();
            return redirect()->intended('/order');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $this->orderRepository->delete($id);
            DB::commit();
            return redirect()->intended('/order');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


}
