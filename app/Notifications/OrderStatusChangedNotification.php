<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusChangedNotification extends Notification
{
    public function __construct(
        public Order $order,
        public string $newStatus,
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $statusLabels = [
            'pending' => 'В обработке',
            'completed' => 'Выполнен',
            'cancelled' => 'Отменён',
        ];

        $label = $statusLabels[$this->newStatus] ?? $this->newStatus;

        return (new MailMessage)
            ->subject("Заказ #{$this->order->id} — {$label}")
            ->greeting("Обновление заказа #{$this->order->id}")
            ->line("Статус вашего заказа изменён на: **{$label}**")
            ->line("Сумма: {$this->order->total} $")
            ->action('Мои заказы', url('/orders'))
            ->line('Спасибо, что выбрали AutoParts Shop!');
    }
}
