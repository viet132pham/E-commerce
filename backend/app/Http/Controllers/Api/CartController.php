<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function show(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load(['items.variant.product']);

        return response()->json($this->cartResponse($cart));
    }

    public function addItem(Request $request)
    {
        $data = $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->getOrCreateCart($request);
        $variant = ProductVariant::findOrFail($data['product_variant_id']);

        $item = $cart->items()->where('product_variant_id', $variant->id)->first();
        if ($item) {
            $item->quantity += $data['quantity'];
            $item->save();
        } else {
            $item = $cart->items()->create([
                'product_variant_id' => $variant->id,
                'quantity' => $data['quantity'],
                'price' => $variant->price,
            ]);
        }

        $cart->load(['items.variant.product']);

        return response()->json($this->cartResponse($cart));
    }

    public function updateItem(Request $request, CartItem $cartItem)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->getOrCreateCart($request);
        if ($cartItem->cart_id !== $cart->id) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cartItem->quantity = $data['quantity'];
        $cartItem->save();

        $cart->load(['items.variant.product']);

        return response()->json($this->cartResponse($cart));
    }

    public function removeItem(Request $request, CartItem $cartItem)
    {
        $cart = $this->getOrCreateCart($request);
        if ($cartItem->cart_id !== $cart->id) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cartItem->delete();
        $cart->load(['items.variant.product']);

        return response()->json($this->cartResponse($cart));
    }

    private function getOrCreateCart(Request $request): Cart
    {
        $sessionId = $request->header('X-Cart-Session') ?: $request->input('session_id');

        if ($sessionId) {
            $cart = Cart::where('session_id', $sessionId)->where('status', 'active')->first();
            if ($cart) {
                return $cart;
            }
        }

        $cart = Cart::create([
            'user_id' => null,
            'session_id' => $sessionId ?: (string) Str::uuid(),
            'status' => 'active',
        ]);

        return $cart;
    }

    private function cartResponse(Cart $cart): array
    {
        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return [
            'id' => $cart->id,
            'session_id' => $cart->session_id,
            'items' => $cart->items,
            'subtotal' => $subtotal,
        ];
    }
}
