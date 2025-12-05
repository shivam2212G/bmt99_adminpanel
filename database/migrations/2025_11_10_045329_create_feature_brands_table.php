<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feature_brands', function (Blueprint $table) {
            $table->id('feature_brand_id');
            $table->string('feature_brand_name');
            $table->longText('feature_brand_image')->default('assets/img/icons/misc/leaf-red.png');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_brands');
    }
};
