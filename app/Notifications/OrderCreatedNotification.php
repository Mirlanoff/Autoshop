<?php
namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderCreatedNotification extends Notification
{
    public function __construct(
        public Order $order
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Новый заказ #' . $this->order->id)
            ->greeting('Новый заказ 🚀')

            ->line('Клиент: ' . $this->order->customer_name)
            ->line('Телефон: ' . $this->order->phone)

            ->line('Сумма: ' . $this->order->total . '$')

            ->action('Открыть заказ', url('/admin/orders/' . $this->order->id))

            ->line('Проверьте заказ в админке');
    }
}
