<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = "order_details";
    protected $fillable = ["price", "qty", "discount", "total", "product_id", "order_id"];

    public function product()
    {
        return $this->belongsTo(Product::class, "product_id", "_id");
    }
    public function order()
    {
        return $this->belongsTo(Order::class, "order_id", "_id");
    }
}
