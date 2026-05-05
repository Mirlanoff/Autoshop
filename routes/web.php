<?php

use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*

|--------------------------------------------------------------------------
| ПУБЛИЧНЫЕ МАРШРУТЫ (Витрина магазина)
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('/', 'index')->name('cart.index');
    Route::post('/add', 'add')->name('cart.add');
    Route::post('/{id}/remove', 'remove')->name('cart.remove');
    Route::post('/{id}/update', 'update')->name('cart.update');
});

/*

|--------------------------------------------------------------------------
| ОПЛАТА (Stripe callbacks)
|--------------------------------------------------------------------------
*/
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

/*

|--------------------------------------------------------------------------
| ЛИЧНЫЙ КАБИНЕТ (Только для авторизованных)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/orders', [OrderController::class, 'myOrders'])->name('orders.index');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/checkout', [OrderController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
        $user = $request->user();
        $recentOrders = \App\Models\Order::where('user_id', $user->id)
            ->with('items')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($o) => [
                'id' => $o->id,
                'total' => $o->total,
                'status' => $o->status,
                'payment_status' => $o->payment_status,
                'created_at' => $o->created_at->format('d.m.Y'),
                'items_count' => $o->items->count(),
            ]);

        return Inertia::render('Dashboard', [
            'stats' => [
                'orders_count' => \App\Models\Order::where('user_id', $user->id)->count(),
                'wishlist_count' => $user->wishlist()->count(),
                'total_spent' => \App\Models\Order::where('user_id', $user->id)
                    ->where('payment_status', 'paid')
                    ->sum('total'),
            ],
            'recentOrders' => $recentOrders,
        ]);
    })->name('dashboard');
});

/*

|--------------------------------------------------------------------------
| АДМИН-ПАНЕЛЬ (Только для админов)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
});

require __DIR__.'/auth.php';
