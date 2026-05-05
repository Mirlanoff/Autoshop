<?php

namespace App\Actions\Order;

use App\Models\Order;

class UpdateOrderStatusAction
{
    public function execute(Order $order, string $status): void
    {
        $allowed = [
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
        ];

        if (!in_array($status, $allowed)) {
            abort(400, 'Недопустимый статус');
        }

        $order->update(['status' => $status]);
    }
}
