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
                'user_id'       => auth()->id(),
                'customer_name' => $dto->customerName,
                'phone'         => $dto->phone,
                'address'       => $dto->address,
                'total'         => $cartService->total(),
                'status'        => Order::STATUS_PENDING,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'name'       => $item['name'],
                    'price'      => $item['price'],
                    'quantity'   => $item['quantity'],
                ]);

                Product::where('id', $item['product_id'])
                    ->where('stock', '>=', $item['quantity'])
                    ->decrement('stock', $item['quantity']);
            }

            $cartService->clear();

            return $order;
        });

        SendOrderNotificationJob::dispatch($order);

        return $order;
    }
}
