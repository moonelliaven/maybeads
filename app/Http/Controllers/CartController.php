<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return Cart::with(['user', 'product'])->get();
    }

    public function show($id)
    {
        return Cart::with(['user', 'product'])->findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        return Cart::create($validated);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);

        $validated = $request->validate([
            'user_id' => ['sometimes', 'exists:users,id'],
            'product_id' => ['sometimes', 'exists:products,id'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
        ]);

        $cart->update($validated);

        return $cart;
    }

    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();

        return response()->json([
            'message' => 'Cart item deleted successfully',
        ]);
    }
}
