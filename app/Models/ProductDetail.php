<?php

namespace App\Models;

use App\Traits\HashBarcodeTrait;
use MongoDB\Laravel\Eloquent\Model;

class ProductDetail extends Model
{


    use HashBarcodeTrait;
    protected $table   = "product_detail";
    protected $fillable = ["barcode", "make_in", "color", "product_id"];

    public function product()
    {
        return $this->belongsTo(Product::class, "product_id", "_id");
    }
}
