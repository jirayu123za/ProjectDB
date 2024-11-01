<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Cart_item;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        $cart = Cart::firstOrCreate(
            ['user_id' => $user->user_id],
            ['total_quantity' => 0, 'total_price' => 0]
        );

        $cartItem = Cart_item::where('cart_id', $cart->cart_id)
            ->where('product_id', $data['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } else {
            $product = Product::find($data['product_id']);
            Cart_item::create([
                'cart_id' => $cart->cart_id,
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'],
                'unit_price' => $product->price,
            ]);
        }

        $this->updateCartTotals($cart);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart successfully',
            'cart' => $cart->load('items')
        ]);
    }

    private function updateCartTotals(Cart $cart)
    {
        $totalQuantity = $cart->items->sum('quantity');
        $totalPrice = $cart->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $cart->update([
            'total_quantity' => $totalQuantity,
            'total_price' => $totalPrice,
        ]);
    }

    public function getCartItems()
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.',
            ], 401);
        }
    
        $cart = Cart::with('items.product')
            ->where('user_id', $user->user_id)
            ->first();
    
        if (!$cart) {
            return response()->json([
                'status' => 'success',
                'message' => 'Cart is empty',
                'cart' => [],
            ], 200);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Cart items retrieved successfully',
            'cart' => $cart,
        ], 200);
    }
    
}
