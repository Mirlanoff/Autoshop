<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class SendOrderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Создание экземпляра задачи.
     */
    public function __construct(
        public Order $order
    ) {}

    /**
     * Выполнение задачи.
     */
    public function handle(): void
    {
        // Отправка уведомления на ваш Gmail
        Notification::route('mail', 'marat.geniy2005@gmail.com')
            ->notify(new OrderCreatedNotification($this->order));
    }

    /**
     * Обработка ошибок (Сениор уровень)
     * Выполнится, если все попытки отправить письмо провалятся.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Ошибка отправки Email заказа', [
            'order_id' => $this->order->id,
            'customer' => $this->order->customer_name,
            'error'    => $exception->getMessage(),
        ]);
    }
}
