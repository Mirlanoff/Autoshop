<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Order\UpdateOrderStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with('items')
            ->when($request->search, function ($query, $search) {
                $query->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Orders/Index', [
            'orders'  => OrderResource::collection($orders),
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items');

        return Inertia::render('Admin/Orders/Show', [
            'order' => (new OrderResource($order))->resolve(),
        ]);
    }

    public function updateStatus(Request $request, Order $order, UpdateOrderStatusAction $action)
    {
        $action->execute($order, $request->status);

        return back()->with('success', 'Статус обновлён');
    }
}
