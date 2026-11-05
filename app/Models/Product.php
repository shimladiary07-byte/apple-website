<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'description',
        'category',
        'product_type',
        'origin',
        'grade',
        'harvest_season',
        'crispness',
        'sweetness',
        'price_per_kg',
        'box_options',
        'image_url',
        'gallery_images',
        'stock_status',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'box_options' => 'array',
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price_per_kg' => 'float',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name).'-'.rand(100, 999);
            }
        });
    }
}
