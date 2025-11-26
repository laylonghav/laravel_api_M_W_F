<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Purchase extends Model
{
    protected $connection = "mongodb";
    protected $table = "purchases";
    protected $fillable = ["supplier_id", "shipping_cost", "paid", "paid_date"];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, "supplier_id", "_id");
    }

    public function productPurchase()
    {
        return $this->hasMany(ProductPurchase::class, "purchase_id", "_id");
    }
}
