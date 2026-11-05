<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('category')->default('Fresh Apples'); // e.g. Table Apples, Sweet & Crisp, Organic, Cider Apples
            $table->string('origin')->default('Shimla / Kinnaur, Himachal Pradesh');
            $table->string('grade')->default('AAA Grade - Handpicked Export Quality');
            $table->string('harvest_season')->default('August - November');
            $table->string('crispness')->default('Very High (Crunchy)'); // Medium, High, Very High
            $table->string('sweetness')->default('Naturally Sweet & Juicy');
            $table->decimal('price_per_kg', 10, 2)->default(180.00);
            $table->json('box_options')->nullable(); // e.g. [{"size": "5 Kg Box (~20-24 apples)", "price": 850, "in_stock": true}, ...]
            $table->string('image_url')->nullable();
            $table->json('gallery_images')->nullable();
            $table->enum('stock_status', ['in_stock', 'pre_order', 'out_of_stock'])->default('in_stock');
            $table->boolean('is_featured')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
