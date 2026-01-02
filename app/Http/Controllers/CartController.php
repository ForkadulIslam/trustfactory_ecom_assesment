<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = auth()->user();
        $cart = Cart::with('cartItems.product')->where('user_id', $user->id)->first();

        return Inertia::render('Cart/Index', [
            'cart' => $cart,
        ]);
    }

    /**
     * Add a product to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $user = auth()->user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $product->stock_quantity) {
                $newQuantity = $product->stock_quantity;
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            $quantity = $request->quantity;
            if ($quantity > $product->stock_quantity) {
                $quantity = $product->stock_quantity;
            }
            $cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    /**
     * Update the quantity of a cart item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->quantity === 0) {
            $cartItem->delete();
            return back()->with('success', 'Product removed from cart!');
        }

        if ($request->quantity > $cartItem->product->stock_quantity) {
            $cartItem->quantity = $cartItem->product->stock_quantity;
        } else {
            $cartItem->quantity = $request->quantity;
        }
        $cartItem->save();

        return back()->with('success', 'Cart updated!');
    }

    /**
     * Remove an item from the cart.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $cartItem->delete();

        return back()->with('success', 'Product removed from cart!');
    }
}
