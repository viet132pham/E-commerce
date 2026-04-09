<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentController extends ApiController
{
    public function createStripeCheckoutSession(Request $request)
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'session_id' => ['nullable', 'string'],
            'success_url' => ['nullable', 'string'],
            'cancel_url' => ['nullable', 'string'],
        ]);

        $order = Order::with('items')->findOrFail($data['order_id']);

        if ($order->status !== 'pending') {
            return $this->success($order, 'Order is not payable', 400);
        }

        $sessionId = $request->header('X-Cart-Session') ?: $data['session_id'] ?? null;

        if ($order->user_id) {
            if (! $request->user() || $request->user()->id !== $order->user_id) {
                return $this->success(null, 'Forbidden', 403);
            }
        } elseif ($sessionId && $order->session_id !== $sessionId) {
            return $this->success(null, 'Forbidden', 403);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $currency = strtolower($order->currency ?? 'usd');

        $lineItems = $order->items->map(function ($item) use ($currency) {
            return [
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $item->name_snapshot,
                    ],
                    'unit_amount' => (int) round(((float) $item->price) * 100),
                ],
                'quantity' => $item->quantity,
            ];
        })->values()->all();

        $session = StripeSession::create([
            'mode' => 'payment',
            'line_items' => $lineItems,
            'success_url' => $data['success_url'] ?? 'http://localhost:3000/checkout/success',
            'cancel_url' => $data['cancel_url'] ?? 'http://localhost:3000/cart',
            'metadata' => [
                'order_id' => (string) $order->id,
            ],
        ]);

        $order->stripe_session_id = $session->id;
        $order->stripe_payment_intent_id = $session->payment_intent ?? null;
        $order->save();

        return $this->success([
            'checkout_url' => $session->url,
            'session_id' => $session->id,
        ], 'Created', 201);
    }

    public function stripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['message' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id ?? null;

            if ($orderId) {
                $order = Order::find($orderId);
                if ($order && $order->status !== 'paid') {
                    $order->status = 'paid';
                    $order->stripe_payment_intent_id = $session->payment_intent ?? $order->stripe_payment_intent_id;
                    $order->paid_at = now();
                    $order->save();
                }
            }
        }

        return response()->json(['received' => true]);
    }
}
