<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $sessionId = $request->header('X-Cart-Session') ?: $request->input('session_id');

        $query = Order::query()->with(['items', 'addresses']);
        if ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        $orders = $query->orderBy('id', 'desc')->get();

        return response()->json($orders);
    }

    public function show(Request $request, Order $order)
    {
        $sessionId = $request->header('X-Cart-Session') ?: $request->input('session_id');

        if ($sessionId && $order->session_id !== $sessionId) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->load(['items', 'addresses']);

        return response()->json($order);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'session_id' => ['required', 'string'],
            'address.name' => ['required', 'string', 'max:255'],
            'address.phone' => ['required', 'string', 'max:50'],
            'address.line1' => ['required', 'string', 'max:255'],
            'address.line2' => ['nullable', 'string', 'max:255'],
            'address.city' => ['required', 'string', 'max:100'],
            'address.state' => ['nullable', 'string', 'max:100'],
            'address.postal_code' => ['nullable', 'string', 'max:20'],
            'address.country' => ['required', 'string', 'max:2'],
        ]);

        $cart = Cart::where('session_id', $data['session_id'])->where('status', 'active')->first();
        if (! $cart || $cart->items()->count() === 0) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $cart->load(['items.variant']);

        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $order = Order::create([
            'user_id' => null,
            'session_id' => $data['session_id'],
            'status' => 'pending',
            'subtotal' => $subtotal,
            'discount_total' => 0,
            'tax_total' => 0,
            'shipping_total' => 0,
            'total' => $subtotal,
            'currency' => 'USD',
            'placed_at' => Carbon::now(),
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $item->product_variant_id,
                'name_snapshot' => $item->variant->product->name,
                'sku_snapshot' => $item->variant->sku,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'total' => $item->price * $item->quantity,
            ]);
        }

        OrderAddress::create([
            'order_id' => $order->id,
            'type' => 'shipping',
            'name' => $data['address']['name'],
            'phone' => $data['address']['phone'],
            'line1' => $data['address']['line1'],
            'line2' => $data['address']['line2'] ?? null,
            'city' => $data['address']['city'],
            'state' => $data['address']['state'] ?? null,
            'postal_code' => $data['address']['postal_code'] ?? null,
            'country' => $data['address']['country'],
        ]);

        $cart->status = 'converted';
        $cart->save();

        $order->load(['items', 'addresses']);

        return response()->json($order, 201);
    }
}
