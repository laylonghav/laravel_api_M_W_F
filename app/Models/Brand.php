<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Brand extends Model
{

    protected $table = "brands";
    protected $fillable = ["name", "description"];


    public function product()
    {
        return $this->hasMany(Product::class, "brand_id", "_id");
    }
}
