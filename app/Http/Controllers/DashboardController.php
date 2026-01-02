<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
        /**
         * Handle the incoming request.
         */
        public function __invoke(Request $request): Response
        {
            $productCount = Product::count();
            $user = Auth::user();
            $cartItemCount = 0;
    
            if ($user) {
                $cart = Cart::where('user_id', $user->id)->first();
                if ($cart) {
                    
                    $cartItemCount = (int) $cart->cartItems()->sum('quantity');
                }
            }
    
            return Inertia::render('Dashboard', [
                'productCount' => $productCount,
                'cartItemCount' => $cartItemCount,
            ]);
        }
    
        /**
         * Display a listing of the products.
         */
        public function shop(): Response
        {
            return Inertia::render('Shop', [
                'products' => Product::all(),
            ]);
        }
    }
    