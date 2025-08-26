<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 relative overflow-hidden">
        <!-- Login Form -->
        <div id="login-form" class="form transition-all duration-500">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Đăng nhập</h2>
            <form class="space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                <div>
                    <label for="login-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="login-email" name="email" type="email" required class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-300" placeholder="Nhập email của bạn">
                </div>
                <div>
                    <label for="login-password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                    <input id="login-password" name="password" type="password" required class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-300" placeholder="Nhập mật khẩu">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-2 rounded-lg font-semibold hover:from-indigo-600 hover:to-purple-700 transition-all duration-300">Đăng nhập</button>
            </form>
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Hoặc tiếp tục với</span>
                </div>
            </div>
            <a href="" class="w-full flex items-center justify-center bg-white border border-gray-300 rounded-lg py-2 px-4 hover:bg-gray-50 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" fill="#EA4335"/>
                    <path d="M46.98 24.55c0-1.7-.15-3.34-.43-4.9H24v9.27h12.88c-.56 2.98-2.25 5.5-4.78 7.18l7.73 5.97c4.52-4.17 7.15-10.32 7.15-17.52z" fill="#4285F4"/>
                    <path d="M10.54 28.78l-7.98-6.19C.99 25.76 0 29.29 0 33c0 3.73.99 7.24 2.56 10.41l7.98-6.19c-1.24-2.22-1.96-4.83-1.96-7.22 0-2.39.72-5 1.96-7.22z" fill="#FBBC05"/>
                    <path d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-5.97c-2.19 1.47-4.95 2.34-8.16 2.34-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" fill="#34A853"/>
                </svg>
                <span class="text-gray-700 font-semibold">Đăng nhập với Google</span>
            </a>
            <p class="text-center text-sm text-gray-600 mt-4">Chưa có tài khoản? <a href="{{ route('register') }}" id="show-register" class="text-indigo-600 hover:underline">Đăng ký ngay</a></p>
        </div>
    </div>
</body>
</html>
