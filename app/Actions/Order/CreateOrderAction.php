<?php

namespace App\Actions\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Services\Cart\CartService;
use App\DTO\Order\CreateOrderDTO;
use App\Jobs\SendOrderNotificationJob;

class CreateOrderAction
{
    public function execute(CreateOrderDTO $dto, CartService $cartService): Order
    {
        $order = DB::transaction(function () use ($dto, $cartService) {
            $cartItems = $cartService->get();

            if (empty($cartItems)) {
                abort(400, 'Корзина пуста');
            }

            $order = Order::create([
                'customer_name' => $dto->customerName,
                'phone' => $dto->phone,
                'total' => $cartService->total(),
                'status' => Order::STATUS_PENDING,
                'payment_method' => $dto->paymentMethod,
                'payment_status' => $dto->paymentMethod === Order::PAYMENT_ONLINE
                    ? Order::PAYMENT_STATUS_PENDING
                    : Order::PAYMENT_STATUS_PAID,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                Product::where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);
            }

            $cartService->clear();

            return $order;
        });

        SendOrderNotificationJob::dispatch($order);

        return $order;
    }
}
