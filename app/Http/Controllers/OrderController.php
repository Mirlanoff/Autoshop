<?php

namespace App\Http\Controllers;

use App\DTO\Order\OrderFilterDTO;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\DTO\Order\CreateOrderDTO;
use App\Actions\Order\CreateOrderAction;

class OrderController extends Controller
{public function index(Request $request)
{
    $filters = OrderFilterDTO::fromRequest($request);

    $orders = Order::query()

        ->when($filters->search, function ($q, $search) {
            $q->where('customer_name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        })

        ->when($filters->status, function ($q, $status) {
            $q->where('status', $status);
        })

        ->latest()
        ->paginate(10)
        ->withQueryString();

    return Inertia::render('Admin/Orders/Index', [
        'orders' => \App\Http\Resources\OrderResource::collection($orders),
        'filters' => $filters,
    ]);
}
    public function create()
    {
        return Inertia::render('Order/Create');
    }
    public function store(StoreOrderRequest $request, CreateOrderAction $action)
    {
        $dto = CreateOrderDTO::fromRequest($request);

        $action->execute($dto, app(\App\Services\Cart\CartService::class));

        return redirect('/')->with('success', 'Заказ оформлен');
    }
    public function show(Order $order)
    {
        $order->load('items');

        return Inertia::render('Admin/Orders/Show', [
            'order' => new OrderResource($order)
        ]);
    }
}
