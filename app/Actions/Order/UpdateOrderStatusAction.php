<?php

namespace App\Actions\Order;

use App\Models\Order;
use App\Notifications\OrderStatusChangedNotification;

class UpdateOrderStatusAction
{
    public function execute(Order $order, string $status): void
    {
        if (!in_array($status, [
            Order::STATUS_PENDING,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
        ])) {
            abort(400);
        }

        $order->update([
            'status' => $status,
        ]);

        if ($order->user) {
            $order->user->notify(new OrderStatusChangedNotification($order, $status));
        }
    }
}
