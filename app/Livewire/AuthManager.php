<?php

namespace App\Livewire;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Mail\EmailVerificationMail;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Livewire\Component;

class AuthManager extends Component
{
    // Login properties
    public $loginEmail = '';
    public $loginPassword = '';
    public $remember = false;

    // Register properties
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $password = '';
    public $passwordConfirmation = '';
    public $dateOfBirth = '';
    public $gender = '';
    public $address = '';
    public $captcha = '';

    // Forgot password properties
    public $forgotEmail = '';

    // Reset password properties
    public $resetToken = '';
    public $resetEmail = '';
    public $resetPassword = '';
    public $resetPasswordConfirmation = '';

    // Profile properties
    public $profileFirstName = '';
    public $profileLastName = '';
    public $profileDateOfBirth = '';
    public $profileGender = '';
    public $profileAddress = '';

    // Change password properties
    public $currentPassword = '';
    public $newPassword = '';
    public $newPasswordConfirmation = '';

    // Order tracking
    public $orderNumber = '';
    public $returnReason = '';

    public $user = null;
    public $orders = [];
    public $recentOrders = [];
    public $order = null;

    public function mount()
    {
        $this->user = Auth::user();
        if ($this->user) {
            $this->loadUserData();
        }
    }

    public function loadUserData()
    {
        if ($this->user) {
            // Split full name into first and last name
            $nameParts = explode(' ', $this->user->name, 2);
            $this->profileFirstName = $nameParts[0] ?? '';
            $this->profileLastName = $nameParts[1] ?? '';
            $this->profileDateOfBirth = $this->user->date_of_birth;
            $this->profileGender = $this->user->gender;
            $this->profileAddress = $this->user->address;
        }
    }

    public function handleLogin()
    {
        // Rate limiting for login attempts
        $throttleKey = 'login.' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('loginEmail', 'Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau ' . $seconds . ' giây.');
            return;
        }

        $this->validate([
            'loginEmail' => ['required', 'email'],
            'loginPassword' => ['required'],
        ]);

        $credentials = [
            'email' => $this->loginEmail,
            'password' => $this->loginPassword,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            // Clear rate limiter on successful login
            RateLimiter::clear($throttleKey);

            request()->session()->regenerate();

            // Check if email is verified
            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();

                $this->addError('loginEmail', 'Vui lòng xác thực email trước khi đăng nhập.');
                return;
            }

            $this->user = Auth::user();
            $this->loadUserData();
            $this->dispatch('showSuccess', 'Đăng nhập thành công!');
            return redirect()->intended('/');
        }

        // Increment rate limiter on failed attempt
        RateLimiter::hit($throttleKey, 300); // 5 minutes

        $this->addError('loginEmail', 'Email hoặc mật khẩu không chính xác.');
    }

    public function handleRegister()
    {
        $this->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'dateOfBirth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'captcha' => 'required',
        ]);

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('reCAPTCHA_SECRET'),
            'response' => $this->captcha,
            'remoteip' => request()->ip(),
        ]);

        if ($response->json()['success'] == false) {
            $this->addError('captcha', 'Captcha verification failed. Please try again.');
            return;
        }

        $user = User::create([
            'name' => $this->lastName . ' ' . $this->firstName,
            'email' => $this->email,
            'date_of_birth' => $this->dateOfBirth,
            'address' => $this->address,
            'gender' => $this->gender,
            'password' => bcrypt($this->password),
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

            $this->dispatch('showSuccess', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản trước khi đăng nhập.');
            return redirect('/login');
        } catch (\Exception $e) {
            // If email fails, delete the user and show error
            $user->delete();

            $this->addError('email', 'Không thể gửi email xác thực. Vui lòng thử lại sau.');
        }
    }

    public function sendResetLinkEmail()
    {
        $this->validate([
            'forgotEmail' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $this->forgotEmail)->first();

        if (!$user) {
            $this->addError('forgotEmail', 'Email không tồn tại trong hệ thống.');
            return;
        }

        // Rate limiting: Check if email was sent recently
        $throttleKey = 'password_reset.' . $this->forgotEmail;

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('forgotEmail', "Vui lòng chờ {$seconds} giây trước khi gửi lại email đặt lại mật khẩu.");
            return;
        }

        // Check if there's an existing reset record and if it's too recent
        $existingReset = DB::table('password_resets')
            ->where('email', $this->forgotEmail)
            ->first();

        if ($existingReset) {
            $lastSent = Carbon::parse($existingReset->created_at);
            $timeDiff = now()->diffInSeconds($lastSent);

            if ($timeDiff < 30) {
                $remainingSeconds = 30 - $timeDiff;
                $this->addError('forgotEmail', "Vui lòng chờ {$remainingSeconds} giây trước khi gửi lại email đặt lại mật khẩu.");
                return;
            }
        }

        // Generate password reset token
        $token = Str::random(64);

        // Store token in database
        DB::table('password_resets')->updateOrInsert(
            ['email' => $this->forgotEmail],
            [
                'email' => $this->forgotEmail,
                'token' => $token,
                'created_at' => now()
            ]
        );

        // Generate reset URL
        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($this->forgotEmail));

        // Send email
        try {
            Mail::to($user->email)->send(new PasswordResetMail($user, $resetUrl));

            // Hit rate limiter after successful email send
            RateLimiter::hit($throttleKey, 30); // 30 seconds cooldown

            $this->dispatch('showSuccess', 'Link đặt lại mật khẩu đã được gửi đến email của bạn! Vui lòng chờ 30 giây trước khi gửi lại.');
        } catch (\Exception $e) {
            $this->addError('forgotEmail', 'Không thể gửi email. Vui lòng thử lại sau.');
        }
    }

    public function resetPasswordUpdate()
    {
        $this->validate([
            'resetToken' => 'required',
            'resetEmail' => 'required|email',
            'resetPassword' => 'required|min:8|confirmed',
        ]);

        // Find the reset record
        $resetRecord = DB::table('password_resets')
            ->where('email', $this->resetEmail)
            ->where('token', $this->resetToken)
            ->first();

        if (!$resetRecord) {
            $this->addError('resetEmail', 'Token không hợp lệ hoặc đã bị xóa.');
            return;
        }

        // Check if token is expired (60 minutes)
        $tokenCreated = Carbon::parse($resetRecord->created_at);
        $isExpired = $tokenCreated->addHours(1)->isPast();

        if ($isExpired) {
            // Delete expired token
            DB::table('password_resets')
                ->where('email', $this->resetEmail)
                ->delete();

            $this->addError('resetEmail', 'Link đặt lại mật khẩu đã hết hạn. Vui lòng yêu cầu email mới.');
            return;
        }

        // Update user password
        $user = User::where('email', $this->resetEmail)->first();
        if ($user) {
            $user->update([
                'password' => bcrypt($this->resetPassword)
            ]);

            // Delete the reset record
            DB::table('password_resets')
                ->where('email', $this->resetEmail)
                ->delete();

            $this->dispatch('showSuccess', 'Mật khẩu đã được đặt lại thành công! Bạn có thể đăng nhập với mật khẩu mới.');
            return redirect('/login');
        }

        $this->addError('resetEmail', 'Có lỗi xảy ra. Vui lòng thử lại.');
    }

    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->user = null;
        $this->dispatch('showSuccess', 'Đăng xuất thành công!');
        return redirect('/');
    }

    public function updateProfile()
    {
        $this->validate([
            'profileFirstName' => 'required|string|max:255',
            'profileLastName' => 'required|string|max:255',
            'profileDateOfBirth' => 'required|date',
            'profileGender' => 'required|in:male,female,other',
            'profileAddress' => 'required|string|max:500',
        ]);

        // Combine first and last name
        $fullName = $this->profileFirstName . ' ' . $this->profileLastName;

        $this->user->update([
            'name' => $fullName,
            'date_of_birth' => $this->profileDateOfBirth,
            'gender' => $this->profileGender,
            'address' => $this->profileAddress,
        ]);

        $this->dispatch('showSuccess', 'Thông tin cá nhân đã được cập nhật thành công!');
    }

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|confirmed',
        ]);

        // Verify current password
        if (!password_verify($this->currentPassword, $this->user->password)) {
            $this->addError('currentPassword', 'Mật khẩu hiện tại không chính xác.');
            return;
        }

        // Update password
        $this->user->update([
            'password' => bcrypt($this->newPassword)
        ]);

        // Logout user after password change for security
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->dispatch('showSuccess', 'Mật khẩu đã được thay đổi thành công! Vui lòng đăng nhập lại với mật khẩu mới.');
        return redirect('/login');
    }

    public function loadUserOrders()
    {
        $this->orders = \App\Models\Order::with(['items.book'])
            ->where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function loadRecentOrders()
    {
        $this->recentOrders = \App\Models\Order::with(['items.book'])
            ->where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function trackOrder()
    {
        if (empty($this->orderNumber)) {
            $this->dispatch('showError', 'Vui lòng nhập mã đơn hàng');
            return;
        }

        $this->order = \App\Models\Order::with(['items.book', 'items.book.author'])
            ->where('order_number', $this->orderNumber)
            ->where('user_id', $this->user->id)
            ->first();

        if (!$this->order) {
            $this->dispatch('showError', 'Không tìm thấy đơn hàng');
        }
    }

    public function markDelivered($orderNumber)
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)
            ->where('user_id', $this->user->id)
            ->first();

        if (!$order) {
            $this->dispatch('showError', 'Không tìm thấy đơn hàng');
            return;
        }

        // Chỉ cho phép đánh dấu đã nhận hàng nếu đơn hàng đang ở trạng thái 'shipped'
        if ($order->status !== 'shipped') {
            $this->dispatch('showError', 'Chỉ có thể đánh dấu đã nhận hàng khi đơn hàng đã được giao');
            return;
        }

        $order->update(['status' => 'delivered']);
        $this->dispatch('showSuccess', 'Đã đánh dấu đơn hàng là đã nhận hàng');
        
        // Refresh orders
        $this->loadUserOrders();
    }

    public function returnOrder($orderNumber)
    {
        $this->validate([
            'returnReason' => 'required|string|max:500',
        ]);

        $order = \App\Models\Order::where('order_number', $orderNumber)
            ->where('user_id', $this->user->id)
            ->first();

        if (!$order) {
            $this->dispatch('showError', 'Không tìm thấy đơn hàng');
            return;
        }

        // Chỉ cho phép hoàn hàng nếu đơn hàng đã được giao hoặc đã nhận hàng
        if (!in_array($order->status, ['shipped', 'delivered'])) {
            $this->dispatch('showError', 'Chỉ có thể hoàn hàng khi đơn hàng đã được giao hoặc đã nhận hàng');
            return;
        }

        $order->update([
            'status' => 'returned',
            'notes' => $order->notes . "\n\nLý do hoàn hàng: " . $this->returnReason
        ]);

        $this->dispatch('showSuccess', 'Đã gửi yêu cầu hoàn hàng. Chúng tôi sẽ liên hệ với bạn sớm nhất');
        $this->returnReason = '';
        
        // Refresh orders
        $this->loadUserOrders();
    }

    public function render()
    {
        return view('livewire.auth-manager');
    }
}
