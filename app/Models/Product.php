<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $collection = "products";
    protected $fillable = ["name", "description", "qty",  "image", "image_url", "image_public_id", "status", "price", "discount", "category_id", "brand_id"];

    // protected $appends = ["image_url"];

    // public function getImageUrlAttribute()
    // {
    //     if (!empty($this->image)) {
    //         return asset("storage/" . $this->image);
    //     }

    //     return null;
    // }


    public function detail()
    {
        return $this->hasOne(ProductDetail::class, "product_id", "_id");
    }

    public function category()
    {
        return $this->belongsTo(Category::class, "category_id", "_id");
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, "brand_id", "_id");
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, "order_id", "_id");
    }

    public function productPurchase()
    {
        return $this->hasMany(ProductPurchase::class, "product_id", "_id");
    }
}
