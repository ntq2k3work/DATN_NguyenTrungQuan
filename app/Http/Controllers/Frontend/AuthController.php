<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Mail\EmailVerificationMail;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

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
        return view('pages.auth.forgot-password');
    }

    public function resetPassword(Request $request, $token)
    {
        return view('pages.auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

        public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.']);
        }

        // Rate limiting: Check if email was sent recently
        $throttleKey = 'password_reset.' . strtolower($request->email);

        // Check Laravel rate limiter (should always be integer seconds)
        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = (int) RateLimiter::availableIn($throttleKey);
            if ($seconds > 0 && $seconds < 3600) {
                return back()->withErrors([
                    'email' => "Vui lòng chờ {$seconds} giây trước khi gửi lại email đặt lại mật khẩu."
                ]);
            } else {
                // fallback for any unexpected value
                return back()->withErrors([
                    'email' => "Bạn đã gửi quá nhiều yêu cầu. Vui lòng thử lại sau."
                ]);
            }
        }

        // Check if there's an existing reset record and if it's too recent
        $existingReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if ($existingReset) {
            $lastSent = Carbon::parse($existingReset->created_at);
            $timeDiff = now()->diffInSeconds($lastSent, false); // negative if in future

            if ($timeDiff < 30 && $timeDiff >= 0) {
                $remainingSeconds = 30 - $timeDiff;
                return back()->withErrors([
                    'email' => "Vui lòng chờ {$remainingSeconds} giây trước khi gửi lại email đặt lại mật khẩu."
                ]);
            }
        }

        // Generate password reset token
        $token = Str::random(64);

        // Store token in database
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]
        );

        // Generate reset URL
        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($request->email));

        // Send email
        try {
            Mail::to($user->email)->send(new PasswordResetMail($user, $resetUrl));

            // Hit rate limiter after successful email send
            RateLimiter::hit($throttleKey, 30);

            return back()->with('status', 'Link đặt lại mật khẩu đã được gửi đến email của bạn! Vui lòng chờ 30 giây trước khi gửi lại.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Không thể gửi email. Vui lòng thử lại sau.']);
        }
    }

    public function resetPasswordUpdate(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Find the reset record
        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Token không hợp lệ hoặc đã bị xóa.']);
        }

        // Check if token is expired (60 minutes)
        $tokenCreated = Carbon::parse($resetRecord->created_at);
        $isExpired = $tokenCreated->addHours(1)->isPast();

        if ($isExpired) {
            // Delete expired token
            DB::table('password_resets')
                ->where('email', $request->email)
                ->delete();

            return back()->withErrors([
                'email' => 'Link đặt lại mật khẩu đã hết hạn. Vui lòng yêu cầu email mới.',
                'token_expired' => true
            ]);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);

            // Delete the reset record
            DB::table('password_resets')
                ->where('email', $request->email)
                ->delete();

            return redirect()->route('login')->with('status', 'Mật khẩu đã được đặt lại thành công! Bạn có thể đăng nhập với mật khẩu mới.');
        }

        return back()->withErrors(['email' => 'Có lỗi xảy ra. Vui lòng thử lại.']);
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

            // Dispatch event to update header counts
            event('user.logged.in');

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

        // Dispatch event to reset header counts
        event('user.logged.out');

        return redirect('/')->with('status', 'Đăng xuất thành công!');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('pages.auth.profile', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::user();

        // Split full name into first and last name
        $nameParts = explode(' ', $user->name, 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        return view('pages.auth.edit-profile', compact('user', 'firstName', 'lastName'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        // Combine first and last name
        $fullName = $request->first_name . ' ' . $request->last_name;

        $user->update([
            'name' => $fullName,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
        ]);

        return redirect()->route('profile')->with('status', 'Thông tin cá nhân đã được cập nhật thành công!');
    }

    public function changePassword()
    {
        return view('pages.auth.change-password');
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        // Update password
        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        // Logout user after password change for security
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Mật khẩu đã được thay đổi thành công! Vui lòng đăng nhập lại với mật khẩu mới.');
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

        // Send verification email (always send verification email regardless of notification settings)
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

    /**
     * Show email settings page
     */
    public function emailSettings()
    {
        $user = Auth::user();
        return view('pages.auth.email-settings', compact('user'));
    }

    /**
     * Update email notification settings
     */
    public function updateEmailSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email_notifications_enabled' => 'boolean',
        ]);

        $user->update([
            'email_notifications_enabled' => $request->boolean('email_notifications_enabled'),
        ]);

        return redirect()->route('email-settings')->with('status', 'Cài đặt thông báo email đã được cập nhật thành công!');
    }
}
