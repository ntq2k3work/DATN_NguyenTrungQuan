<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\EmailVerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

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

    public function verifyEmail(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect('/login')->withErrors(['email' => 'Người dùng không tồn tại.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/login')->with('status', 'Email đã được xác thực trước đó.');
        }

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect('/login')->withErrors(['email' => 'Link xác thực không hợp lệ.']);
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        return redirect('/login')->with('status', 'Email đã được xác thực thành công! Bạn có thể đăng nhập ngay bây giờ.');
    }

    public function confirmPassword()
    {
        // return view('pages.auth.confirm-password');
    }

    public function handleLogin(Request $request)
    {
        // Rate limiting for login attempts
        $throttleKey = 'login.' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => 'Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau ' . $seconds . ' giây.',
            ]);
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Clear rate limiter on successful login
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();

            // Check if email is verified
            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Vui lòng xác thực email trước khi đăng nhập.',
                ])->onlyInput('email');
            }

            return redirect()->intended('/')->with('status', 'Đăng nhập thành công!');
        }

        // Increment rate limiter on failed attempt
        RateLimiter::hit($throttleKey, 300); // 5 minutes

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Đăng xuất thành công!');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('pages.auth.profile', compact('user'));
    }

    public function handleRegister(RegisterRequest $request)
    {
        $request->validated();

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
            'name' => $request->last_name . ' ' . $request->first_name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'gender' => $request->gender,
            'password' => bcrypt($request->password),
        ]);

        // Generate verification URL
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        // Send verification email
        try {
            Mail::to($user->email)->send(new EmailVerificationMail($user, $verificationUrl));

            return redirect('/login')->with('status', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản trước khi đăng nhập.');
        } catch (\Exception $e) {
            // If email fails, delete the user and show error
            $user->delete();

            return back()->withErrors([
                'email' => 'Không thể gửi email xác thực. Vui lòng thử lại sau.',
            ])->onlyInput('email');
        }
    }
}
