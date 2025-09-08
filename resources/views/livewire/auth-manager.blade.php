<div>
    <!-- Login Form -->
    @if(!$user)
        <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Đăng nhập vào tài khoản
                    </h2>
                </div>
                
                <form wire:submit.prevent="handleLogin" class="mt-8 space-y-6">
                    <div class="rounded-md shadow-sm -space-y-px">
                        <div>
                            <label for="loginEmail" class="sr-only">Email</label>
                            <input id="loginEmail" 
                                   wire:model="loginEmail" 
                                   type="email" 
                                   required 
                                   class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                                   placeholder="Địa chỉ email">
                            @error('loginEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="loginPassword" class="sr-only">Mật khẩu</label>
                            <input id="loginPassword" 
                                   wire:model="loginPassword" 
                                   type="password" 
                                   required 
                                   class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                                   placeholder="Mật khẩu">
                            @error('loginPassword') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" 
                                   wire:model="remember" 
                                   type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-900">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                                Quên mật khẩu?
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Đăng nhập
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <span class="text-sm text-gray-600">Chưa có tài khoản? </span>
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            Đăng ký ngay
                        </a>
                    </div>
                </form>
            </div>
        </div>
    @else
        <!-- User Profile -->
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold mb-8">Thông tin cá nhân</h1>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Profile Info -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-semibold mb-6">Thông tin cá nhân</h2>
                            
                            <form wire:submit.prevent="updateProfile" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Họ</label>
                                        <input type="text" 
                                               wire:model="profileLastName" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('profileLastName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tên</label>
                                        <input type="text" 
                                               wire:model="profileFirstName" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('profileFirstName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" 
                                           value="{{ $user->email }}" 
                                           disabled
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày sinh</label>
                                        <input type="date" 
                                               wire:model="profileDateOfBirth" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('profileDateOfBirth') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Giới tính</label>
                                        <select wire:model="profileGender" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="male">Nam</option>
                                            <option value="female">Nữ</option>
                                            <option value="other">Khác</option>
                                        </select>
                                        @error('profileGender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                                    <textarea wire:model="profileAddress" 
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                    @error('profileAddress') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" 
                                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                                        Cập nhật thông tin
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Change Password -->
                        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                            <h2 class="text-xl font-semibold mb-6">Đổi mật khẩu</h2>
                            
                            <form wire:submit.prevent="updatePassword" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu hiện tại</label>
                                    <input type="password" 
                                           wire:model="currentPassword" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('currentPassword') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                                    <input type="password" 
                                           wire:model="newPassword" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('newPassword') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu mới</label>
                                    <input type="password" 
                                           wire:model="newPasswordConfirmation" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('newPasswordConfirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" 
                                            class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 transition duration-200">
                                        Đổi mật khẩu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-lg font-semibold mb-4">Tài khoản</h2>
                            
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm text-gray-600">Email:</span>
                                    <p class="font-medium">{{ $user->email }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm text-gray-600">Tham gia:</span>
                                    <p class="font-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm text-gray-600">Trạng thái:</span>
                                    <p class="font-medium text-green-600">Đã xác thực</p>
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-6 border-t">
                                <button wire:click="logout"
                                        class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-200">
                                    Đăng xuất
                                </button>
                            </div>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                            <h2 class="text-lg font-semibold mb-4">Thao tác nhanh</h2>
                            
                            <div class="space-y-2">
                                <a href="{{ route('orders.index') }}" 
                                   class="block w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 text-center">
                                    Đơn hàng của tôi
                                </a>
                                
                                <a href="{{ route('orders.track') }}" 
                                   class="block w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200 text-center">
                                    Theo dõi đơn hàng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
