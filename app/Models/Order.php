<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";
    protected $fillable = ["order_no", "paid_amount", "total_amount", "payment_method"];

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, "order_id", "_id");
    }
}
