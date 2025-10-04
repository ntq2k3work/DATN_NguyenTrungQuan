<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminAuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        // Nếu đã đăng nhập và là admin, redirect đến Filament admin panel
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect('/admin');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Kiểm tra user có phải admin không
            if (!$user->isAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tài khoản này không có quyền truy cập trang admin',
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended('/admin')
                ->with('success', 'Đăng nhập thành công! Chào mừng bạn đến với trang quản trị.');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Đăng xuất thành công!');
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Lấy thống kê cơ bản
        $totalUsers = User::where('role', 'user')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalBooks = \App\Models\Book::count();
        $totalOrders = \App\Models\Order::count();
        $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        $completedOrders = \App\Models\Order::where('status', 'completed')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalBooks',
            'totalOrders',
            'pendingOrders',
            'completedOrders'
        ));
    }
}
