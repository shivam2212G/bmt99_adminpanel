<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appuser;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class GoogleAuthController extends Controller
{

    public function login(Request $request)
    {
        $idToken = $request->input('id_token');

        if (!$request->has('id_token')) {
            return response()->json(['error' => 'id_token is required'], 400);
        }


        // Verify the token with Google
        $googleResponse = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $idToken,
        ]);

        if ($googleResponse->failed()) {
            return response()->json(['error' => 'Invalid Google token'], 401);
        }

        $googleUser = $googleResponse->json();

        // Check if token belongs to your Google Client ID
        if ($googleUser['aud'] !== env('GOOGLE_CLIENT_ID')) {
            return response()->json(['error' => 'Invalid client ID'], 403);
        }

        // Find or create user
        $user = Appuser::updateOrCreate(
            ['google_id' => $googleUser['sub']],
            [
                'name' => $googleUser['name'] ?? null,
                'email' => $googleUser['email'] ?? null,
                'avatar' => $googleUser['picture'] ?? null,
                'email_verified' => isset($googleUser['email_verified']) ? (int) $googleUser['email_verified'] : 1,
                'email_verified_at' => now(),
                'login_type' => 'google',
            ]
        );

        // Generate Laravel token (for API access)
        $token = base64_encode(bin2hex(random_bytes(32))); // Simple random token
        $user->app_token = $token;
        $user->save();

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function editProfile(Request $request, $id)
    {
        $user = AppUser::find($id);

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "User not found",
            ], 404);
        }

        // return $user;

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // return $request->all();

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->has('address')) $user->address = $request->address;
        if ($request->has('avatar')) $user->avatar = $request->avatar;

        // FIXED: only remove old image if it's local (not a Google URL)
        if ($request->hasFile('avatar')) {

            if ($user->avatar && !str_contains($user->avatar, 'http')) {
                $oldPath = public_path($user->avatar);
                if (file_exists($oldPath)) @unlink($oldPath);
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('avatars/'), $filename);
            $user->avatar = "avatars/" . $filename;
        }

        $user->save();

        return response()->json([
            "status" => true,
            "message" => "Profile updated successfully",
            "data" => $user
        ]);
    }




    // public function editProfile(Request $request, $id)
    // {
    //     $user = AppUser::find($id);

    //     if (!$user) {
    //         return response()->json([
    //             "status" => false,
    //             "message" => "User not found",
    //         ], 404);
    //     }

    //     // -------------------------------
    //     // VALIDATION
    //     // -------------------------------
    //     $validated = $request->validate([
    //         'name' => 'nullable|string|max:255',
    //         'phone' => 'nullable|string|max:20',
    //         'address' => 'nullable|string',
    //         'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg',
    //     ]);

    //     // -------------------------------
    //     // UPDATE FIELDS
    //     // -------------------------------
    //     if ($request->has('name')) {
    //         $user->name = $request->name;
    //     }

    //     if ($request->has('phone')) {
    //         $user->phone = $request->phone;
    //     }

    //     if ($request->has('address')) {
    //         $user->address = $request->address;
    //     }

    //     // -------------------------------
    //     // HANDLE AVATAR UPLOAD
    //     // -------------------------------
    //     if ($request->hasFile('avatar')) {

    //         // delete old avatar if exists
    //         if ($user->avatar && file_exists(public_path($user->avatar))) {
    //             @unlink(public_path($user->avatar));
    //         }

    //         $file = $request->file('avatar');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $file->move(public_path('avatars/'), $filename);

    //         $user->avatar = "avatars/" . $filename;
    //     }

    //     $user->save();

    //     return response()->json([
    //         "status" => true,
    //         "message" => "Profile updated successfully",
    //         "data" => $user
    //     ]);
    // }
}
