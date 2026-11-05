<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('shipping_address');
            $table->string('city');
            $table->string('state');
            $table->string('pincode');
            $table->text('order_notes')->nullable();
            $table->json('items'); // [{product_id, product_name, box_size, price, quantity, total}]
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method')->default('cod'); // cod, upi_whatsapp, online
            $table->string('payment_status')->default('pending'); // pending, paid, verified
            $table->string('status')->default('pending'); // pending, confirmed, packed, dispatched, delivered, cancelled
            $table->string('tracking_courier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
