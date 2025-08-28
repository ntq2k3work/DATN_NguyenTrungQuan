@extends('layouts.app')
@section('title', 'Quên mật khẩu')
@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16">
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Quên mật khẩu
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Nhập email của bạn để nhận link đặt lại mật khẩu
        </p>
        <div class="mt-2 text-center">
            <p class="text-xs text-gray-500">
                ⏱️ Chống spam: Chỉ cho phép gửi email sau mỗi 30 giây
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            @if (session('status'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                    <div class="mt-2 text-sm text-green-600">
                        <p>📧 Email đã được gửi thành công!</p>
                        <p>⏰ Vui lòng chờ 30 giây trước khi gửi lại email.</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                    <div class="flex items-center mb-2">
                        <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">Có lỗi xảy ra:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Địa chỉ email
                    </label>
                    <div class="mt-1">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            value="{{ old('email') }}"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                            placeholder="Nhập email của bạn"
                        >
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                    >
                        Gửi link đặt lại mật khẩu
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <!-- Security Info -->
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                    <div class="flex items-center text-sm text-blue-700">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">🔒 Bảo mật:</span>
                    </div>
                    <ul class="mt-2 text-xs text-blue-600 space-y-1">
                        <li>• Link đặt lại mật khẩu có hiệu lực trong 60 phút</li>
                        <li>• Chỉ cho phép gửi email sau mỗi 30 giây để chống spam</li>
                        <li>• Token được mã hóa và bảo mật</li>
                        <li>• Tự động xóa token hết hạn để bảo mật</li>
                    </ul>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Hoặc</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3">
                    <a
                        href="{{ route('login') }}"
                        class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors duration-200"
                    >
                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Quay lại đăng nhập
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
