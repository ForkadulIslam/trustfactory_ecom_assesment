<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Jobs\LowStockNotificationJob;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = auth()->user();
        $orders = Order::with('orderItems.product')
                        ->where('user_id', $user->id)
                        ->latest()
                        ->paginate(10);

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $cart = Cart::with('cartItems.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        DB::transaction(function () use ($cart, $user) {
            $totalPrice = $cart->cartItems->reduce(function ($carry, $item) {
                return $carry + ($item->product->price * $item->quantity);
            }, 0);

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice / 100,
                'status' => 'completed',
            ]);

            foreach ($cart->cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price / 100,
                ]);

                // Update product stock and check for low stock
                $product = $cartItem->product;
                $product->stock_quantity -= $cartItem->quantity;
                $product->save();

                $lowStockThreshold = 5;
                if ($product->stock_quantity <= $lowStockThreshold) {
                    LowStockNotificationJob::dispatch($product);
                }
            }

            $cart->delete();
        });

        return redirect()->route('shop')->with('success', 'Order placed successfully!');
    }
}
