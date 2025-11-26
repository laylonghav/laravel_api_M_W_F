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
        Schema::create('order_details', function (Blueprint $collection) {
            $collection->id();
            $collection->string("price");
            $collection->string("qty");
            $collection->string("discount");
            $collection->string("total");

            $collection->objectId("product_id");
            $collection->objectId("order_id");
            $collection->index("product_id");
            $collection->index("order_id");

            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
