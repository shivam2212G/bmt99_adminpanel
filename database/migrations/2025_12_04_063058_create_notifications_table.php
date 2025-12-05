<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->string('title');
            $table->text('message');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('appusers')->onDelete('cascade');
            $table->longText('image')->nullable();
            $table->timestamps();
        });

        // Insert 15 default notifications
        DB::table('notifications')->insert([
            [
                'title' => 'Welcome to BMT99!',
                'message' => 'Thank you for installing our app.',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Big Offer Today!',
                'message' => 'Get 20% OFF on all dairy products.',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Special Discount',
                'message' => 'Flat ₹50 OFF on orders above ₹499.',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'New Product Arrivals',
                'message' => 'Check out the latest fresh items added today.',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Limited Stock Alert',
                'message' => 'Hurry! Few items left in stock.',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Weekend Mega Sale',
                'message' => 'Up to 40% OFF on selected groceries.',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Free Delivery Offer',
                'message' => 'Free delivery on orders above ₹299 today!',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Your Order is Packed',
                'message' => 'Order #1001 is packed and ready to ship!',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Refer & Earn',
                'message' => 'Invite friends and earn ₹50 for each signup.',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Today\'s Fresh Deals',
                'message' => 'Don’t miss today’s exclusive discounts.',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Price Drop Alert!',
                'message' => 'Your favourite product is now cheaper.',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Daily Essentials',
                'message' => 'Best deals on essentials available now.',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Flash Sale!',
                'message' => 'Only for 2 hours — huge discounts on all items.',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Thank You!',
                'message' => 'We appreciate your continued support.',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Your Wishlist Awaits',
                'message' => 'Products in your wishlist are selling fast!',
                'image' => null,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
