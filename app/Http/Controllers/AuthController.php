<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
   // Show login page
    public function showLoginForm()
    {
        return view('pages.sign-in');
    }

    // Handle login form submit
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email-username' => 'required|string',
            'password' => 'required|string',
        ]);

        $login_type = filter_var($credentials['email-username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$login_type => $credentials['email-username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email-username' => 'Invalid credentials, please try again.',
        ]);
    }

    // Dashboard page
    public function dashboard()
    {
        $user = Auth::user();
        return view('pages.dashboard', compact('user'));
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('sign-in')->with('success', 'You have been logged out.');
    }


}
