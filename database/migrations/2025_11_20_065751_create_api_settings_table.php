<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('new_products');
            $table->integer('best_offers');
            $table->integer('less_in_stock');
            $table->longText('shop_address');
            $table->string('shop_phone')->nullable();
            $table->string('shop_email')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->longText('privacy_policy')->default('Our privacy policy goes here.');
            $table->longText('discamer')->default('Our disclaimer goes here.');
            $table->timestamps();
        });

        // Insert default record
        DB::table('api_settings')->insert([
            'new_products' => 5,
            'best_offers' => 20,
            'less_in_stock' => 50,
            'shop_address' => '123 Main St, Anytown, USA',
            'privacy_policy' => 'Our privacy policy goes here.',
            'discamer' => 'Our disclaimer goes here.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_settings');
    }
};
