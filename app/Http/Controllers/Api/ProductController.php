<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $hasFilters = $request->boolean('all') ||
            $request->filled('category') ||
            $request->filled('product_type') ||
            $request->filled('stock_status') ||
            $request->filled('search');

        if (! $hasFilters) {
            $products = Cache::remember('public_active_products', 300, function () {
                return Product::where('is_active', true)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->get();
            });

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        }

        $query = Product::query();

        // If admin request with all=true, return all including inactive
        if (! $request->boolean('all')) {
            $query->where('is_active', true);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('tagline', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('origin', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function show($idOrSlug)
    {
        $product = Product::where('id', $idOrSlug)
            ->orWhere('slug', $idOrSlug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    public function store(Request $request)
    {
        if ($request->filled('box_options') && is_string($request->box_options)) {
            $decoded = json_decode($request->box_options, true);
            if (is_array($decoded)) {
                $request->merge(['box_options' => $decoded]);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'product_type' => 'nullable|string|in:apple,other',
            'origin' => 'nullable|string|max:255',
            'grade' => 'nullable|string|max:255',
            'harvest_season' => 'nullable|string|max:100',
            'crispness' => 'nullable|string|max:100',
            'sweetness' => 'nullable|string|max:100',
            'price_per_kg' => 'required|numeric|min:0',
            'box_options' => 'nullable|array',
            'image_url' => 'nullable|string',
            'gallery_images' => 'nullable|array',
            'stock_status' => 'required|in:in_stock,pre_order,out_of_stock',
            'is_featured' => 'nullable',
            'is_active' => 'nullable',
            'sort_order' => 'nullable|integer',
        ]);

        if (! isset($validated['product_type'])) {
            $validated['product_type'] = 'apple';
        }
        if (isset($validated['is_featured'])) {
            $validated['is_featured'] = filter_var($validated['is_featured'], FILTER_VALIDATE_BOOLEAN);
        }
        if (isset($validated['is_active'])) {
            $validated['is_active'] = filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN);
        }

        // Auto handle uploaded image file if present
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.Str::slug($request->name).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);
            $validated['image_url'] = '/uploads/products/'.$filename;
        }

        $product = Product::create($validated);
        Cache::forget('public_active_products');

        return response()->json([
            'success' => true,
            'message' => 'Product added successfully!',
            'data' => $product,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->filled('box_options') && is_string($request->box_options)) {
            $decoded = json_decode($request->box_options, true);
            if (is_array($decoded)) {
                $request->merge(['box_options' => $decoded]);
            }
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'sometimes|required|string|max:100',
            'product_type' => 'nullable|string|in:apple,other',
            'origin' => 'nullable|string|max:255',
            'grade' => 'nullable|string|max:255',
            'harvest_season' => 'nullable|string|max:100',
            'crispness' => 'nullable|string|max:100',
            'sweetness' => 'nullable|string|max:100',
            'price_per_kg' => 'sometimes|required|numeric|min:0',
            'box_options' => 'nullable|array',
            'image_url' => 'nullable|string',
            'gallery_images' => 'nullable|array',
            'stock_status' => 'sometimes|required|in:in_stock,pre_order,out_of_stock',
            'is_featured' => 'nullable',
            'is_active' => 'nullable',
            'sort_order' => 'nullable|integer',
        ]);

        if (isset($validated['is_featured'])) {
            $validated['is_featured'] = filter_var($validated['is_featured'], FILTER_VALIDATE_BOOLEAN);
        }
        if (isset($validated['is_active'])) {
            $validated['is_active'] = filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.Str::slug($request->input('name', $product->name)).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);
            $validated['image_url'] = '/uploads/products/'.$filename;
        }

        $product->update($validated);
        Cache::forget('public_active_products');

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'data' => $product,
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        Cache::forget('public_active_products');

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
        ]);
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = ! $product->is_active;
        $product->save();
        Cache::forget('public_active_products');

        return response()->json([
            'success' => true,
            'message' => 'Status updated.',
            'data' => $product,
        ]);
    }
}
