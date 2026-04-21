<?php

use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*

|--------------------------------------------------------------------------
| ПУБЛИЧНЫЕ МАРШРУТЫ (Витрина магазина)
|--------------------------------------------------------------------------
*/

// Теперь ГЛАВНАЯ страница — это каталог товаров
Route::get('/', [ProductController::class, 'index'])->name('products.index');

// Группа маршрутов корзины
Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('/', 'index')->name('cart.index');
    Route::post('/add', 'add')->name('cart.add');
    Route::post('/{id}/remove', 'remove')->name('cart.remove');
    Route::post('/{id}/update', 'update')->name('cart.update');
});

/*

|--------------------------------------------------------------------------
| ЛИЧНЫЙ КАБИНЕТ (Только для авторизованных)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Оформление заказа
    Route::get('/checkout', [OrderController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');

    // Профиль (оставляем от Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard (можно оставить или убрать)
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
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
});

// Стандартные маршруты авторизации Breeze (login, register, logout)
require __DIR__.'/auth.php';
