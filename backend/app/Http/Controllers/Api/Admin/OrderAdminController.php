<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderAdminController extends Controller
{
    public function index()
    {
        return response()->json(Order::with(['items', 'addresses'])->orderBy('id', 'desc')->paginate(20));
    }

    public function show(Order $order)
    {
        $order->load(['items', 'addresses']);

        return response()->json($order);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'string', Rule::in(['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'])],
        ]);

        $order->status = $data['status'];
        $order->save();

        return response()->json($order);
    }
}
