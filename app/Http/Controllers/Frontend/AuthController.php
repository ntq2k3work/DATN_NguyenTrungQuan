<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    public function handleRegister(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required',
        ]);
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('reCAPTCHA_SECRET'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(), // optional
        ]);
        if($response->json()['success'] == false) {
            return back()->withErrors([
                'captcha' => 'Captcha verification failed. Please try again.',
            ])->onlyInput('email');
        }
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),

        ]);

        auth()->login($user);

        return redirect('/');
    }




}
