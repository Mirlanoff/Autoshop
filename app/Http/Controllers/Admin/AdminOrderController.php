<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Actions\Order\UpdateOrderStatusAction;

class AdminOrderController
{
    public function index(Request $request)
    {
        // ИСПРАВЛЕНО: Добавлен with('user') или те связи, которые вы выводите в таблице
        // Также добавлена фильтрация, чтобы поиск в Index.vue работал
        $orders = Order::query()
            ->with(['items']) // Загружаем связи заранее одним запросом
            ->when($request->search, function ($query, $search) {
                $query->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Сохраняет фильтры при переходе по страницам

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
            'filters' => $request->only(['search', 'status']) // Передаем фильтры обратно в Vue
        ]);
    }

    public function show(Order $order)
    {
        // Здесь всё верно: load() используется для загрузки связей конкретной модели
        $order->load('items');

        return Inertia::render('Admin/Orders/Show', [
            'order' => $order
        ]);
    }

    public function updateStatus(Request $request, Order $order, UpdateOrderStatusAction $action)
    {
        $action->execute($order, $request->status);

        return back()->with('success', 'Статус обновлён');
    }
}
