<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'city',
        'state',
        'pincode',
        'order_notes',
        'items',
        'subtotal',
        'shipping_fee',
        'shipping_method',
        'total_amount',
        'payment_method',
        'payment_status',
        'status',
        'tracking_courier',
        'tracking_number',
        'admin_notes',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'float',
        'shipping_fee' => 'float',
        'total_amount' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'APL-'.date('Y').'-'.strtoupper(substr(uniqid(), -5));
            }
        });
    }
}
