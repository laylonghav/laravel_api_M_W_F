<?php

use Illuminate\Database\Migrations\Migration;
use  MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_detail', function (Blueprint $collection) {
            $collection->objectId("product_id");
            $collection->index("product_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_detail', function (Blueprint $table) {
            //
        });
    }
};
