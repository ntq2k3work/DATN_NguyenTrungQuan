@extends('layouts.app')

@section('title')
Đăng nhập - BookStore NTQ
@endsection

@section('content')
<div class="min-h-screen flex items-baseline justify-center p-4">
  <div class="w-full max-w-2xl p-8 md:p-10 rounded-lg shadow-lg">
    <!-- Tabs -->
    <div class="flex justify-center mb-6 text-lg">
      <a href="#" class="px-4  font-bold">Đăng nhập</a>
      <span class="border-l mx-4"></span>
      <a href="{{ route('register') }}" class="px-4 font-semibold text-gray-400">Đăng ký</a>
    </div>

    <!-- Form -->
    <form class="space-y-5 text-base">
      @csrf
      <input type="email" placeholder="Email" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200">
      <input type="password" placeholder="Mật khẩu" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200">

      <p class="text-xs text-gray-500">
        This site is protected by reCAPTCHA and the Google
        <a href="#" class="text-blue-500">Privacy Policy</a> and
        <a href="#" class="text-blue-500">Terms of Service</a> apply.
      </p>

      <div class="flex items-center gap-6">
        <button type="submit" class="bg-red-600 text-white font-bold py-3 px-6 rounded hover:bg-red-700">
          ĐĂNG NHẬP
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