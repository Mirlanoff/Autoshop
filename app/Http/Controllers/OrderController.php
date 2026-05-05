<?php

namespace App\Http\Controllers;

use App\Actions\Order\CreateOrderAction;
use App\DTO\Order\CreateOrderDTO;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Cart\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('items')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Orders/Index', [
            'orders' => OrderResource::collection($orders),
        ]);
    }

    public function create(CartService $cart)
    {
        $items = $cart->get();

        if (empty($items)) {
            return redirect()->route('cart.index')
                ->with('error', 'Корзина пуста');
        }

        return Inertia::render('Orders/Create', [
            'items' => $items,
            'total' => $cart->total(),
        ]);
    }

    public function store(StoreOrderRequest $request, CreateOrderAction $action)
    {
        $dto = CreateOrderDTO::fromRequest($request);

        $action->execute($dto, app(CartService::class));

        return redirect()->route('orders.index')
            ->with('success', 'Заказ успешно оформлен!');
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items');

        return Inertia::render('Orders/Show', [
            'order' => (new OrderResource($order))->resolve(),
        ]);
    }
}
