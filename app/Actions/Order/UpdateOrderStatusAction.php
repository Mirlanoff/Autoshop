<?php
namespace App\Actions\Order;

use App\Models\Order;

class UpdateOrderStatusAction
{
    public function execute(Order $order, string $status): void
    {
        if (!in_array($status, [
            Order::STATUS_PENDING,
            Order::STATUS_COMPLETED
        ])) {
            abort(400);
        }

        $order->update([
            'status' => $status
        ]);
    }
}
