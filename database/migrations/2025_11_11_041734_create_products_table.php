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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('product_name');
            $table->text('product_description')->nullable();
            $table->unsignedBigInteger('product_mrp');
            $table->unsignedBigInteger('product_price');
            $table->unsignedBigInteger('product_discount')->default(0);
            $table->integer('product_stock')->default(0);
            $table->enum('product_unit', ['0','1','2','3','4','5','6']); // 0: piece, 1: kg, 2: gram, 3: liter, 4: ml, 5: pack, 6: box
            $table->longText('product_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('sub_category_id');
            $table->foreign('sub_category_id')->references('sub_category_id')->on('sub_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('feature_brand_id')->nullable();
            $table->foreign('feature_brand_id')->references('feature_brand_id')->on('feature_brands')->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
