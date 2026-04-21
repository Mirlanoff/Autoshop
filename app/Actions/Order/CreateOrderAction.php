<?php

namespace App\Actions\Order;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Services\Cart\CartService;
use App\DTO\Order\CreateOrderDTO;
use App\Jobs\SendOrderNotificationJob;

class CreateOrderAction
{
    public function execute(CreateOrderDTO $dto, CartService $cartService): Order
    {
        // 1. Создаем заказ в транзакции
        $order = DB::transaction(function () use ($dto, $cartService) {
            $cartItems = $cartService->get();

            if (empty($cartItems)) {
                abort(400, 'Корзина пуста');
            }

            $order = Order::create([
                'customer_name' => $dto->customerName,
                'phone'         => $dto->phone,
                'total'         => $cartService->total(),
                'status'        => 'pending', // Хорошая практика: задать статус явно
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'name'       => $item['name'],
                    'price'      => $item['price'],
                    'quantity'   => $item['quantity'],
                ]);

                // Совет: Здесь можно добавить уменьшение остатков товара (stock)
            }

            $cartService->clear();

            return $order;
        });

        // 2. ОТПРАВЛЯЕМ УВЕДОМЛЕНИЕ (вне транзакции)
        // Теперь, если транзакция прошла успешно, ставим задачу в очередь
        SendOrderNotificationJob::dispatch($order);

        return $order;
    }
}
