<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register()
    {
        return view('pages.auth.register');
    }

    public function login()
    {
        return view('pages.auth.login');
    }

    public function forgotPassword()
    {
        // return view('pages.auth.forgot-password');
    }

    public function resetPassword()
    {
        // return view('pages.auth.reset-password');
    }

    public function verifyEmail()
    {
        // return view('pages.auth.verify-email');
    }

    public function confirmPassword()
    {
        // return view('pages.auth.confirm-password');
    }

    public function handleLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }


}
