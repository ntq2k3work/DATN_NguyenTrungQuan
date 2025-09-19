<div>
    @if($loading)
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-500 mx-auto"></div>
            <p class="mt-4 text-gray-600">Đang tải giỏ hàng...</p>
        </div>
    @elseif(count($cartItems) === 0)
        <div class="text-center py-16">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9m-6-9v9"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Giỏ hàng trống</h3>
            <p class="mt-2 text-gray-500">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
            <div class="mt-6">
                <a href="{{ route('home') }}" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors">
                    Bắt đầu mua sắm
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($cartItems as $item)
                @php
                    $book = $item['book'] ?? $item;
                    $quantity = $item['quantity'];
                    $price = $item['price'] ?? $book['final_price'] ?? $book['price'];
                @endphp
                <div class="bg-white rounded-lg shadow-md p-6 flex items-center space-x-6">
                    <img src="{{ asset($book['image_url'] ?? '/images/placeholder-book.jpg') }}"
                         alt="{{ $book['title'] }}"
                         class="w-20 h-28 object-cover rounded">

                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $book['title'] }}</h3>
                        <p class="text-gray-600">{{ $book['author']['name'] ?? 'Unknown Author' }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <p class="text-lg font-bold text-red-600">{{ number_format($price, 0, ',', '.') }}đ</p>
                            <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                Còn lại: {{ $book['quantity'] ?? 0 }} sản phẩm
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button wire:click="updateQuantity({{ $book['id'] }}, {{ $quantity - 1 }})"
                                class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition-colors">
                            -
                        </button>
                        <input type="number"
                               value="{{ $quantity }}"
                               min="1"
                               max="{{ $book['quantity'] ?? 99 }}"
                               wire:input="updateQuantityFromInput({{ $book['id'] }}, $event.target.value)"
                               class="w-16 h-8 text-center border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <button wire:click="updateQuantity({{ $book['id'] }}, {{ $quantity + 1 }})"
                                class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition-colors">
                            +
                        </button>
                    </div>

                    <div class="text-right">
                        <p class="text-lg font-bold text-red-600">{{ number_format($price * $quantity, 0, ',', '.') }}đ</p>
                        <button wire:click="removeFromCart({{ $book['id'] }})"
                                class="text-red-500 hover:text-red-700 text-sm mt-2">
                            Xóa
                        </button>
                    </div>
                </div>
            @endforeach

            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold">Tổng cộng:</h3>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($total, 0, ',', '.') }}đ</p>
                </div>

                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('home') }}" class="flex-1 bg-gray-500 text-white py-3 px-6 rounded-lg hover:bg-gray-600 transition-colors text-center">
                        Tiếp tục mua sắm
                    </a>
                    <a href="{{ route('checkout') }}" class="flex-1 bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition-colors text-center">
                        Thanh toán
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
