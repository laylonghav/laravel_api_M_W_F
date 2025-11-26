<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProductPurchase extends Model
{
    protected $connection = "mongodb";
    protected $table = "product_purchases";
    protected $fillable = ["purchase_id", "product_id", "cost", "qty", "retail_price", "ref", "remark"];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, "purchase_id", "_id");
    }

    public function product()
    {
        return $this->belongsTo(Product::class, "product_id", "_id");
    }
}
