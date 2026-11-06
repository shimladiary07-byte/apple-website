<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate($request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'shipping_address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'order_notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required',
            'items.*.product_name' => 'required|string',
            'items.*.box_size' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cod,upi_whatsapp,online',
            'shipping_method' => 'nullable|string|max:255',
            'shipping_fee' => 'nullable|numeric|min:0',
        ]);

        // Validate payment method toggles
        $enableCod = filter_var(Setting::get('enable_cod', '1'), FILTER_VALIDATE_BOOLEAN);
        $enableUpi = filter_var(Setting::get('enable_upi', '1'), FILTER_VALIDATE_BOOLEAN);

        if ($validated['payment_method'] === 'cod' && ! $enableCod) {
            return response()->json([
                'success' => false,
                'message' => 'Cash on Delivery (COD) is currently disabled by the orchard. Please choose UPI / Online payment.',
            ], 422);
        }

        if ($validated['payment_method'] === 'upi_whatsapp' && ! $enableUpi) {
            return response()->json([
                'success' => false,
                'message' => 'UPI payment is currently disabled. Please choose another payment method.',
            ], 422);
        }

        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        // Calculate Shipping Fee based on admin settings
        $enableFreeShipping = filter_var(Setting::get('enable_free_shipping', '1'), FILTER_VALIDATE_BOOLEAN);
        $freeShippingMin = (float) Setting::get('free_shipping_min_order', 1500);
        $defaultShippingFee = (float) Setting::get('default_shipping_fee', 120);

        $shippingMethod = $validated['shipping_method'] ?? 'Standard Mountain Express';
        $shippingFee = $defaultShippingFee;

        // If client provided a selected shipping fee, verify against options
        $shippingOptionsJson = Setting::get('shipping_options');
        if ($shippingOptionsJson) {
            $options = json_decode($shippingOptionsJson, true);
            if (is_array($options)) {
                foreach ($options as $opt) {
                    if (($opt['id'] ?? '') === $shippingMethod || ($opt['name'] ?? '') === $shippingMethod) {
                        $shippingFee = (float) ($opt['fee'] ?? $defaultShippingFee);
                        $shippingMethod = $opt['name'] ?? $shippingMethod;
                        break;
                    }
                }
            }
        }

        // Apply free shipping rule if qualified
        if ($enableFreeShipping && $freeShippingMin > 0 && $subtotal >= $freeShippingMin) {
            if ($shippingFee <= $defaultShippingFee) {
                $shippingFee = 0.00;
            } else {
                // For premium express, apply discount equal to standard fee
                $shippingFee = max(0.00, $shippingFee - $defaultShippingFee);
            }
        }

        $totalAmount = $subtotal + $shippingFee;

        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => ! empty($validated['customer_email']) ? $validated['customer_email'] : (auth()->check() ? auth()->user()->email : null),
            'shipping_address' => $validated['shipping_address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'pincode' => $validated['pincode'],
            'order_notes' => $validated['order_notes'] ?? null,
            'items' => $validated['items'],
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'shipping_method' => $shippingMethod,
            'total_amount' => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        // Build WhatsApp share link for instant confirmation
        $farmerWhatsApp = Setting::get('whatsapp', '919876543210');
        $upiId = Setting::get('upi_id', 'kinnaurfarm@okhdfcbank');
        $itemsText = '';
        foreach ($order->items as $it) {
            $itemsText .= "• {$it['product_name']} ({$it['box_size']}) x {$it['quantity']} = ₹".($it['price'] * $it['quantity'])."\n";
        }

        $paymentMethodLabel = $order->payment_method === 'cod' ? 'CASH ON DELIVERY (COD)' : 'UPI PAYMENT';

        $waMsg = "🍎 *New Apple Order Placed!*\n\n"
               ."*Order ID:* {$order->order_number}\n"
               ."*Customer:* {$order->customer_name}\n"
               ."*Phone:* {$order->customer_phone}\n"
               ."*Address:* {$order->shipping_address}, {$order->city}, {$order->state} - {$order->pincode}\n"
               ."*Shipping:* {$order->shipping_method} (₹{$order->shipping_fee})\n\n"
               ."*Items:*\n{$itemsText}\n"
               ."*Subtotal:* ₹{$order->subtotal}\n"
               ."*Total Payable:* ₹{$order->total_amount}\n"
               ."*Payment Method:* {$paymentMethodLabel}\n";

        if ($order->payment_method === 'upi_whatsapp') {
            $waMsg .= "*Farmer UPI ID:* {$upiId}\n_(Attaching payment screenshot below)_\n";
        }

        $waMsg .= "\nPlease confirm my fresh apple box delivery! 🍏";

        $whatsappUrl = 'https://wa.me/'.preg_replace('/[^0-9]/', '', $farmerWhatsApp).'?text='.urlencode($waMsg);

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully!',
            'data' => [
                'order' => $order,
                'whatsapp_url' => $whatsappUrl,
            ],
        ], 201);
    }

    public function track(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $queryStr = trim($request->query('query', $request->input('query')));

        $order = Order::where('order_number', $queryStr)
            ->orWhere('customer_phone', $queryStr)
            ->orderBy('created_at', 'desc')
            ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'No order found with this Order ID or Phone number.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    public function myOrders(Request $request)
    {
        if (! auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in to view your orders.',
                'data' => [],
            ], 401);
        }

        $user = auth()->user();

        $orders = Order::where(function ($query) use ($user) {
            $query->where('customer_email', $user->email);
            if (! empty($user->phone)) {
                $query->orWhere('customer_phone', $user->phone);
            }
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,packed,dispatched,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid,refunded',
            'tracking_courier' => 'nullable|string',
            'tracking_number' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully!',
            'data' => $order,
        ]);
    }
}
