@extends('layouts.app')
@section('title', 'Link đã hết hạn')
@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Link đã hết hạn
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Link đặt lại mật khẩu của bạn đã hết hạn sau 60 phút
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <!-- Warning Message -->
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                <div class="flex items-center text-yellow-700">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Thông báo quan trọng</span>
                </div>
                <p class="mt-2 text-sm text-yellow-600">
                    Link đặt lại mật khẩu đã hết hạn để đảm bảo bảo mật.
                    Vui lòng yêu cầu email mới để tiếp tục quá trình đặt lại mật khẩu.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <a href="{{ route('password.request') }}"
                   class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7 7m0 0a6 6 0 01-7-7m7 7v4m0 0H9m3 0h3m-3-4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Yêu cầu email mới
                </a>

                <a href="{{ route('login') }}"
                   class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại đăng nhập
                </a>
            </div>

            <!-- Security Info -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                <div class="flex items-center text-blue-700">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-medium">🔒 Tại sao link hết hạn?</span>
                </div>
                <ul class="mt-2 text-xs text-blue-600 space-y-1">
                    <li>• Bảo mật: Ngăn chặn truy cập trái phép vào tài khoản</li>
                    <li>• Tự động: Hệ thống tự động xóa token hết hạn</li>
                    <li>• An toàn: Đảm bảo quá trình đặt lại mật khẩu an toàn</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
