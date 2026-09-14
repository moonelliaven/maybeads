<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with(['user', 'product'])->get();
    }

    public function show($id)
    {
        return Order::with(['user', 'product'])->findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'product_id' => ['required', 'exists:products,id'],
            'product_name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:1'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'order_status' => ['nullable', 'string', 'max:20'],
            'payment_status' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'phone_number' => ['nullable', 'string'],
            'order_at' => ['nullable', 'date'],
            'send_at' => ['nullable', 'date'],
        ]);

        return Order::create($validated);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'user_id' => ['sometimes', 'exists:users,id'],
            'product_id' => ['sometimes', 'exists:products,id'],
            'product_name' => ['sometimes', 'string', 'max:100'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
            'subtotal' => ['sometimes', 'numeric', 'min:0'],
            'order_status' => ['sometimes', 'string', 'max:20'],
            'payment_status' => ['sometimes', 'string', 'max:20'],
            'address' => ['sometimes', 'string'],
            'phone_number' => ['nullable', 'string'],
            'order_at' => ['nullable', 'date'],
            'send_at' => ['nullable', 'date'],
        ]);

        $order->update($validated);

        return $order;
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully',
        ]);
    }
}
