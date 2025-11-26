<?php

namespace App\Models;

use  MongoDB\Laravel\Eloquent\Model;

class Category extends Model
{
    protected $table = "categories";
    protected $fillable = ["name", "description", "status"];

    public function product()
    {
        return $this->hasMany(Product::class, "category_id", "_id");
    }
}
