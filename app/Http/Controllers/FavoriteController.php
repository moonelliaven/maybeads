<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with('product')
            ->where('user_id', Auth::id())
            ->latest('id')
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
        ]);

        return back()->with('success', 'Produk ditambahkan ke favorit.');
    }

    public function destroy(Favorite $favorite)
    {
        abort_unless($favorite->user_id === Auth::id(), 403);

        $favorite->delete();

        return back()->with('success', 'Produk dihapus dari favorit.');
    }
}
