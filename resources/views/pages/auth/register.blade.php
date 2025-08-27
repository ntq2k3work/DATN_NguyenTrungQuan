@extends('layouts.app')

@section('script')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('title')
Đăng ký - BookStore NTQ
@endsection

@section('content')
<div class="min-h-screen flex items-baseline justify-center p-4">
  <div class="w-full max-w-2xl p-8 md:p-10 rounded-lg shadow-lg">
    <!-- Tabs -->
    <div class="flex justify-center mb-6 text-lg">
      <a href="{{ route('login') }}" class="px-4 font-semibold text-gray-400">Đăng nhập</a>
      <span class="border-l mx-4"></span>
      <a href="#" class="px-4 font-bold text-black">Đăng ký</a>
    </div>

    <!-- Form -->
    <form action="{{ route('handleRegister') }}" method="POST" class="space-y-5 text-base">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="text" placeholder="Họ" name="last_name" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200">
        <input type="text" placeholder="Tên" name="first_name" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200">
      </div>

      <!-- Giới tính -->
      <div class="flex items-center space-x-6 text-gray-700">
        <label class="flex items-center space-x-2">
          <input type="radio" name="gender" value="female" class="h-4 w-4">
          <span>Nữ</span>
        </label>
        <label class="flex items-center space-x-2">
          <input type="radio" name="gender" value="male" class="h-4 w-4">
          <span>Nam</span>
        </label>
      </div>

      <!-- Ngày sinh / Email / Mật khẩu -->
      <input type="date" name="date_of_birth" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200">
      <input type="text" name="address" placeholder="Nhập địa chỉ" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200">
      <input type="email" placeholder="Email" name="email" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200">
      <input type="password" placeholder="Mật khẩu" name="password" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200">
      <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200">

      <p class="text-xs text-gray-500">
        <div class="g-recaptcha" data-sitekey="6LcLFbQrAAAAACCQxNzJgYvvKNT0ZkJsKm0Hnb7o"></div>
      </p>

      <div class="flex items-center gap-6">
        <button type="submit" class="bg-red-600 text-white font-bold py-3 px-6 rounded hover:bg-red-700">
          ĐĂNG KÝ
        </button>
        <div class="text-sm text-gray-700">
          Bạn đã có tài khoản?
          <a href="#" class="text-blue-600 ml-2">Đăng nhập ngay</a>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection
