<?php

namespace App\Http\Repositories\User;
use App\Models\User;

class UserRepository
{
    private User $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function create(mixed $data)
    {
        return $this->model->create($data);
    }
}
