<?php

// ======= app/Http/Controllers/CartController.php =======
namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::all();
        return view('cart.index', compact('cartItems'));
    }

    public function create()
    {
        return view('cart.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        Cart::create($request->all());
        return redirect()->route('cart.index');
    }

    public function show($id)
    {
        $cartItem = Cart::findOrFail($id);
        return view('cart.show', compact('cartItem'));
    }

    public function edit($id)
    {
        $cartItem = Cart::findOrFail($id);
        return view('cart.edit', compact('cartItem'));
    }

    public function update(Request $request, $id)
    {
        $cartItem = Cart::findOrFail($id);

        $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $cartItem->update($request->all());
        return redirect()->route('cart.index');
    }

    public function destroy($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();
        return redirect()->route('cart.index');
    }
}
