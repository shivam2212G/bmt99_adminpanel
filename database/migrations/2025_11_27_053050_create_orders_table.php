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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('total_amount');
            $table->unsignedInteger('discount_amount')->default(0);
            $table->unsignedInteger('final_amount');
            $table->unsignedInteger('delivery_charge')->default(0);
            $table->longText('address');
            $table->string('phone');
            $table->enum('payment_method',['0','1'])->default(1); // 0: Cash on Delivery, 1: Online Payment
            $table->enum('payment_status',['0','1','2'])->default(0); // 0: Pending, 1: Completed , 2: Failed
            $table->longText('transaction_id')->nullable();
            $table->unsignedBigInteger('paid_amount')->nullable();
            $table->enum('order_status', ['0','1','2','3','4','5'])->default('0'); // 0: Pending, 1: Confirmed, 2: Shipped, 3: Delivered, 4: Cancelled, 5: Returned
            $table->longText('cancel_reason')->nullable();
            $table->foreign('user_id')->references('id')->on('appusers')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('price'); // final selling price
            $table->unsignedInteger('mrp');   // original MRP
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
