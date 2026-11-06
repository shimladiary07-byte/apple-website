<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

// Public APIs
Route::prefix('api')->group(function () {
    // Farm Settings
    Route::get('/settings', [SettingController::class, 'index']);

    // Catalog Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{idOrSlug}', [ProductController::class, 'show']);

    // Orders
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/track', [OrderController::class, 'track']);

    // Inquiries / Bulk Orders
    Route::post('/inquiries', [InquiryController::class, 'store']);

    // Auth
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Admin APIs (Protected: Only users with is_admin = true can access)
    Route::prefix('admin')->middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'stats']);

        // Products CRUD
        Route::post('/products', [ProductController::class, 'store']);
        Route::post('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        Route::patch('/products/{id}/toggle-status', [ProductController::class, 'toggleStatus']);

        // Orders Management
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);

        // Inquiries Management
        Route::get('/inquiries', [InquiryController::class, 'index']);
        Route::patch('/inquiries/{id}/read', [InquiryController::class, 'markRead']);
        Route::delete('/inquiries/{id}', [InquiryController::class, 'destroy']);

        // Farm Settings update
        Route::post('/settings', [SettingController::class, 'update']);
    });
});

// Vue Single Page App catch-all route
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');
