<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_purchases', function (Blueprint $collection) {
            $collection->id();
            $collection->objectId("purchase_id");
            $collection->objectId("product_id");
            $collection->index("purchase_id");
            $collection->index("product_id");

            $collection->string("cost");
            $collection->string("qty");
            $collection->string("retail_price");
            $collection->string("ref");
            $collection->string("remark");
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_purchases');
    }
};
