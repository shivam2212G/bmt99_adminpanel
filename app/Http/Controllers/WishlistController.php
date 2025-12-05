<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function toggleWishlist(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
        ]);

        // Check if already in wishlist
        $exists = Wishlist::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($exists) {
            // Remove From Wishlist
            $exists->delete();

            return response()->json([
                'status' => 'removed',
                'message' => 'Removed from wishlist',
            ], 200);
        }

        // Add New Wishlist Item
        $wishlist = Wishlist::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'status' => 'added',
            'message' => 'Added to wishlist',
            'wishlist' => $wishlist,
        ], 201);
    }
    
    public function getWishlist($user_id)
    {
        $items = Wishlist::with('product')   // Make sure you define relation in model
            ->where('user_id', $user_id)
            ->get();

        return response()->json([
            'count' => $items->count(),
            'items' => $items
        ]);
    }
}
