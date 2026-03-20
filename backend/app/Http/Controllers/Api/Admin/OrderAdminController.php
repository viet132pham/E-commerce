<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderAdminController extends ApiController
{
    public function index()
    {
        return $this->paginated(Order::with(['items', 'addresses'])->orderBy('id', 'desc')->paginate(20));
    }

    public function show(Order $order)
    {
        $order->load(['items', 'addresses']);

        return $this->success($order);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'string', Rule::in(['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'])],
        ]);

        $order->status = $data['status'];
        $order->save();

        return $this->success($order);
    }
}
