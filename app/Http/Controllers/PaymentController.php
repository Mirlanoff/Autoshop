<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Payment\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function success(Request $request, StripeService $stripe)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect('/')->with('error', 'Сессия оплаты не найдена');
        }

        $session = $stripe->retrieveSession($sessionId);

        $order = Order::where('stripe_session_id', $sessionId)->first();

        if (!$order) {
            return redirect('/')->with('error', 'Заказ не найден');
        }

        if ($session->payment_status === 'paid') {
            $order->update([
                'payment_status' => Order::PAYMENT_STATUS_PAID,
            ]);
        }

        return Inertia::render('Payment/Success', [
            'order' => [
                'id' => $order->id,
                'total' => (float) $order->total,
                'payment_status' => $order->payment_status,
            ],
        ]);
    }

    public function cancel(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = $orderId ? Order::find($orderId) : null;

        return Inertia::render('Payment/Cancel', [
            'order' => $order ? [
                'id' => $order->id,
                'total' => (float) $order->total,
            ] : null,
        ]);
    }

    public function webhook(Request $request, StripeService $stripe)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = $stripe->constructWebhookEvent($payload, $signature);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $order = Order::where('stripe_session_id', $session->id)->first();

            if ($order && $session->payment_status === 'paid') {
                $order->update([
                    'payment_status' => Order::PAYMENT_STATUS_PAID,
                ]);
            }
        }

        return response('OK', 200);
    }
}
