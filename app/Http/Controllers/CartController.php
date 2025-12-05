<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // 1. ADD TO CART  (POST)
    // -------------------------------
    public function addToCart(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'nullable',
        ]);


        // Check if product already exists in cart for this user
        $existing = Cart::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->first();


        if ($existing) {
            $finalQuantity = $existing->quantity + $request->quantity ?? 1;
            $existing->quantity = $finalQuantity;
            $existing->save();

            $count = $existing->quantity;

            $message = "Added {$request->quantity}. Now you have {$count} in cart.";

            return response()->json([
                'message' =>  $message,
                'cart' => $existing
            ], 200);
        }



        $cart = Cart::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity ?? 1,
        ]);

        return response()->json([
            'message' => 'Product added to cart successfully',
            'cart' => $cart
        ], 201);
    }


    // -------------------------------
    // 2. REMOVE FROM CART (DELETE)
    // -------------------------------
    public function removeFromCart($cart_id)
    {
        $cart = Cart::find($cart_id);

        if (!$cart) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cart->delete();

        return response()->json([
            'message' => 'Item removed from cart successfully'
        ], 200);
    }


    // -------------------------------
    // 3. GET CART ITEMS FOR USER (GET)
    // -------------------------------
    public function getCart($user_id)
    {
        $items = Cart::with('product')->where('user_id', $user_id)->get();

        return response()->json([
            'count' => $items->count(),
            'items' => $items
        ], 200);
    }


    public function updateAllQuantities(Request $request)
    {
        $request->validate([
            "user_id" => "required|integer",
            "items" => "required|array",
        ]);

        foreach ($request->items as $item) {

            // Each item must have cart_id & quantity
            if (!isset($item['cart_id']) || !isset($item['quantity'])) {
                continue;
            }

            Cart::where("user_id", $request->user_id)
                ->where("cart_id", $item["cart_id"])
                ->update([
                    "quantity" => $item["quantity"]
                ]);
        }

        return response()->json([
            "status" => true,
            "message" => "Cart updated successfully",
        ]);
    }
}
