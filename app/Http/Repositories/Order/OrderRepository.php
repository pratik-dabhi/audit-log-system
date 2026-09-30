<?php

namespace App\Http\Repositories\Order;
use App\Models\Order;

class OrderRepository
{
    private Order $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    public function get()
    {
        return $this->model->get();
    }

    public function create(mixed $data)
    {
        return $this->model->create($data);
    }

    public function getById(int $id)
    {
        return $this->model->find($id);
    }

    public function update(int $id, mixed $data)
    {
        $order = $this->model->find($id);
        return $order->update($data);
    }

    public function delete(int $id)
    {
        $order = $this->model->find($id);
        return $order->delete();
    }

}
