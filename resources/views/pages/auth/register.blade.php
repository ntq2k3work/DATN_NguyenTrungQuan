<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 relative overflow-hidden">
        <!-- Register Form -->
        <div id="register-form" class="form transition-all duration-500">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Đăng ký</h2>
            <form class="space-y-6" method="POST" action="{{ route('register') }}">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Tên</label>
                    <input id="name" name="name" type="text" required class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-300" placeholder="Nhập tên của bạn">
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Địa điểm</label>
                    <input id="location" name="location" type="text" required class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-300" placeholder="Nhập địa điểm">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" required class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-300" placeholder="Nhập email của bạn">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                    <input id="password" name="password" type="password" required class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-300" placeholder="Nhập mật khẩu">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-300" placeholder="Xác nhận mật khẩu">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-2 rounded-lg font-semibold hover:from-indigo-600 hover:to-purple-700 transition-all duration-300">Đăng ký</button>
            </form>
            <p class="text-center text-sm text-gray-600 mt-4">Đã có tài khoản? <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Đăng nhập</a></p>
        </div>
    </div>
</body>
</html>
