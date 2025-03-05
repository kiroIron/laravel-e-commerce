<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('order_number')->unique();
            $table->json('products'); // Stores list of products from the cart
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending'); // pending, shipped, delivered
            // Delivery address fields
            $table->string('city');
            $table->string('address');
            $table->string('building_number');
            // Payment fields
            $table->string('payment_method')->nullable(); // e.g., stripe, paypal
            $table->string('payment_status')->default('not_paid'); // paid, not_paid
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
