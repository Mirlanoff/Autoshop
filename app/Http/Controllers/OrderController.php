<?php

namespace App\Http\Controllers;

use App\DTO\Order\OrderFilterDTO;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Payment\StripeService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\DTO\Order\CreateOrderDTO;
use App\Actions\Order\CreateOrderAction;

class OrderController extends Controller
{
    public function index(Request $request)
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
            'orders' => OrderResource::collection($orders),
            'filters' => $filters,
        ]);
    }

    public function create(CartService $cart)
    {
        return Inertia::render('Order/Create', [
            'cartItems' => $cart->get(),
            'cartTotal' => $cart->total(),
            'stripeEnabled' => !empty(config('services.stripe.key')),
        ]);
    }

    public function store(StoreOrderRequest $request, CreateOrderAction $action, StripeService $stripe)
    {
        $dto = CreateOrderDTO::fromRequest($request);
        $cartService = app(CartService::class);

        $order = $action->execute($dto, $cartService);

        if ($request->input('payment_method') === 'online' && !empty(config('services.stripe.secret'))) {
            $order->load('items');
            $session = $stripe->createCheckoutSession($order);

            return Inertia::location($session->url);
        }

        return redirect('/')->with('success', 'Заказ #' . $order->id . ' оформлен! Оплата при получении.');
    }

    public function show(Order $order)
    {
        $order->load('items');

        return Inertia::render('Admin/Orders/Show', [
            'order' => new OrderResource($order),
        ]);
    }
}
