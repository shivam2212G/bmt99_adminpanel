<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class bOrderController extends Controller
{
    public function index()
    {
        $allOrders = Order::with('items.product')->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // return $allOrders;

        return view('pages.orders.index', compact('allOrders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product', 'user')->findOrFail($id);

        return view('pages.orders.details', compact('order'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'status'   => 'required|in:0,1,2,3,4,5',
        ]);

        $order = Order::find($request->order_id);
        $order->order_status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'status'  => $order->order_status
        ]);
    }
}
