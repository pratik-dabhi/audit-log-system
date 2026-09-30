<?php

namespace App\Models;

use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['status', 'amount', 'user_id'])]
#[ObservedBy([OrderObserver::class])]
class Order extends Model
{
    protected function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
