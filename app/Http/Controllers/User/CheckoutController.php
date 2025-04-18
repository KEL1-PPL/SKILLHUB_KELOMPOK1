<?php

// ======= app/Http/Controllers/CheckoutController.php =======
namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::all();  // Or fetch user's cart items
        return view('checkout.index', compact('cartItems'));
    }

    public function create()
    {
        return view('checkout.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        // Store the checkout data or handle the order processing
        // For example, store order in an 'orders' table or trigger a payment gateway API call

        // Clear the cart after checkout
        Cart::truncate();

        return redirect()->route('checkout.index')->with('success', 'Your order has been placed!');
    }

    public function show($id)
    {
        // You can show the checkout details or the order summary here.
        return view('checkout.show', compact('id'));
    }

    public function edit($id)
    {
        // Implement the ability to edit checkout details
        return view('checkout.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update the checkout details, like address or payment method
    }
}
