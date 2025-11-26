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
        Schema::table('products', function (Blueprint $collection) {
            $collection->objectId("product_detail_id");
            $collection->index("product_detail_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $collection) {
            $collection->dropIndex("product_detail_id");
            $collection->dropColumn("product_detail_id");
        });
    }
};
