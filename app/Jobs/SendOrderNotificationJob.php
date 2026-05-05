<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendOrderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function handle(): void
    {
        $adminEmail = config('mail.from.address', 'admin@autoparts.kg');

        Notification::route('mail', $adminEmail)
            ->notify(new OrderCreatedNotification($this->order));
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Ошибка отправки Email заказа', [
            'order_id' => $this->order->id,
            'customer' => $this->order->customer_name,
            'error'    => $exception->getMessage(),
        ]);
    }
}
