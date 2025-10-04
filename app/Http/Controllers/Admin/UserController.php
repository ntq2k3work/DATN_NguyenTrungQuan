<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Tìm kiếm theo tên hoặc email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Lọc theo role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Tên người dùng là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'role.required' => 'Vai trò là bắt buộc',
            'role.in' => 'Vai trò không hợp lệ',
            'date_of_birth.before' => 'Ngày sinh phải trước ngày hôm nay',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'email_verified_at' => now(), // Tự động verify email cho admin tạo
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'Tạo người dùng thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi tạo người dùng. Vui lòng thử lại.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Lấy thêm thông tin đơn hàng của user
        $orders = $user->orders()->latest()->paginate(10);
        
        return view('admin.users.show', compact('user', 'orders'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
                'max:255'
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Tên người dùng là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'role.required' => 'Vai trò là bắt buộc',
            'role.in' => 'Vai trò không hợp lệ',
            'date_of_birth.before' => 'Ngày sinh phải trước ngày hôm nay',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
            ];

            // Chỉ cập nhật password nếu có nhập
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('admin.users.index')
                ->with('success', 'Cập nhật người dùng thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật người dùng. Vui lòng thử lại.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Không cho phép xóa chính mình
            if ($user->id === auth()->id()) {
                return back()->with('error', 'Không thể xóa tài khoản của chính mình!');
            }

            // Không cho phép xóa nếu user có đơn hàng
            if ($user->orders()->exists()) {
                return back()->with('error', 'Không thể xóa người dùng đã có đơn hàng!');
            }

            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'Xóa người dùng thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xóa người dùng. Vui lòng thử lại.');
        }
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(User $user)
    {
        try {
            // Không cho phép thay đổi trạng thái chính mình
            if ($user->id === auth()->id()) {
                return back()->with('error', 'Không thể thay đổi trạng thái của chính mình!');
            }

            $user->update([
                'email_verified_at' => $user->email_verified_at ? null : now()
            ]);

            $status = $user->email_verified_at ? 'kích hoạt' : 'vô hiệu hóa';
            
            return back()->with('success', "Đã {$status} người dùng thành công!");
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi thay đổi trạng thái người dùng.');
        }
    }
}
