<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    public function placeOrder(Request $request)
    {
        // ---------------- VALIDATION ---------------- //
        $request->validate([
            'user_id'        => 'required|exists:appusers,id',
            'payment_method' => 'required|in:0,1',   // 0 COD, 1 Online
            'address'        => 'required',
            'phone'          => 'required',
            'transaction_id'  => 'required_if:payment_method,1',
            'paid_amount'    => 'required_if:payment_method,1',
        ]);

        $userId = $request->user_id;

        // ---------------- GET CART ITEMS ---------------- //
        $cartItems = Cart::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty',
            ], 400);
        }

        // ---------------- CALCULATE TOTALS ---------------- //
        $totalMRP = 0;
        $totalSellingPrice = 0;

        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);

            $quantity = $item->quantity ?? 1;

            $totalMRP          += $product->product_mrp * $quantity;
            $totalSellingPrice += $product->product_price * $quantity;
        }

        $discount       = $totalMRP - $totalSellingPrice;
        $deliveryCharge = $totalSellingPrice > 500 ? 0 : 40;
        $finalAmount    = $totalSellingPrice + $deliveryCharge;


        // ---------------- CREATE ORDER ---------------- //
        $order = Order::create([
            'user_id'         => $userId,
            'total_amount'    => $totalMRP,
            'discount_amount' => $discount,
            'final_amount'    => $finalAmount,
            'delivery_charge' => $deliveryCharge,
            'address'         => $request->address,
            'phone'           => $request->phone,
            'payment_method'  => $request->payment_method,
            'transaction_id'   => $request->transaction_id ?? null,
            'paid_amount'     => $request->paid_amount ?? null,
            'payment_status'  => $request->payment_method == 1 ? '1' : '0',
            'order_status'    => '0', // pending
        ]);

        // ---------------- CREATE ORDER ITEMS ---------------- //
        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);
            $quantity = $item->quantity ?? 1;

            OrderItem::create([
                'order_id'   => $order->order_id,
                'product_id' => $product->product_id,
                'quantity'   => $quantity,
                'price'      => $product->product_price,
                'mrp'        => $product->product_mrp,
            ]);
        }

        // ---------------- CLEAR CART ---------------- //
        Cart::where('user_id', $userId)->delete();

        // ---------------- RETURN SUCCESS ---------------- //
        return response()->json([
            'status' => true,
            'message' => 'Order placed successfully',
            'order' => [
                'order_id'        => $order->order_id,
                'total_mrp'       => $totalMRP,
                'total_price'     => $totalSellingPrice,
                'discount'        => $discount,
                'delivery_charge' => $deliveryCharge,
                'final_amount'    => $finalAmount,
                'payment_method'  => $request->payment_method == 0 ? 'COD' : 'Online',
                'address'        =>  $order->address,
            ]
        ], 201);
    }

    public function getOrdersByUser($userId)
    {
        $orders = Order::with('items.product')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'orders' => $orders,
        ], 200);
    }

    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'cancel_reason' => 'required',
        ]);

        $order = Order::find($request->order_id);
        $order->order_status = '4'; // Cancelled
        $order->cancel_reason = $request->cancel_reason;
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Order status updated successfully',
            'order' => $order,
        ], 200);
    }
}
