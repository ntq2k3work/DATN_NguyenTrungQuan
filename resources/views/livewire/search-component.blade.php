<div class="relative flex w-full max-w-2xl">
    <div class="relative w-full">
        <input
            type="text"
            wire:model.live.debounce.300ms="query"
            placeholder="Tìm kiếm sách, tác giả, thể loại..."
            class="w-full px-4 py-2 pl-10 pr-4 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
            id="search-input"
        >

        <!-- Search Icon -->
        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        <!-- Loading Indicator -->
        @if($loading)
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-red-500"></div>
            </div>
        @endif
    </div>

    <!-- Suggestions Dropdown -->
    @if($showSuggestions && count($suggestions) > 0)
        <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-50 max-h-60 overflow-y-auto">
            @foreach($suggestions as $suggestion)
                <div
                    wire:click="selectSuggestion('{{ $suggestion['text'] }}')"
                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm"
                >
                    <div class="flex items-center">
                        <span class="text-gray-600">{{ $this->getSuggestionIcon($suggestion['type']) }}</span>
                        <span class="ml-2">{{ $suggestion['text'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Search Results Dropdown -->
    @if($showResults && count($results) > 0)
        <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto">
            @foreach($results as $result)
                <a
                    href="{{ $result['url'] }}"
                    class="block px-4 py-3 hover:bg-gray-100 border-b border-gray-100 last:border-b-0"
                >
                    <div class="flex items-start space-x-3">
                        <img src="{{ $result['image_url'] }}"
                             alt="{{ $result['title'] }}"
                             class="w-12 h-16 object-cover rounded">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-900 truncate">{{ $result['title'] }}</h3>
                            <div class="text-sm text-gray-600 mt-1">
                                <div>Tác giả: {{ $result['author'] }}</div>
                                <div>Nhà xuất bản: {{ $result['publisher'] }}</div>
                                <div>Danh mục: {{ $result['category'] }}</div>
                            </div>
                            <div class="flex items-center space-x-2 mt-2">
                                @if($result['has_discount'])
                                    <span class="text-lg font-semibold text-red-600">{{ $result['discount_price'] }}đ</span>
                                    <span class="text-sm text-gray-500 line-through">{{ $result['price'] }}đ</span>
                                    <span class="text-xs bg-red-100 text-red-600 px-1 rounded">-{{ $result['discount_percent'] }}%</span>
                                @else
                                    <span class="text-lg font-semibold text-gray-900">{{ $result['price'] }}đ</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach

            <!-- View All Results Link -->
            <a
                href="{{ route('search.results', ['q' => $query]) }}"
                class="block px-4 py-2 text-center bg-gray-50 hover:bg-gray-100"
            >
                <span class="text-sm text-blue-600 font-medium">Xem tất cả kết quả cho "{{ $query }}"</span>
            </a>
        </div>
    @endif

    <!-- No Results -->
    @if($showResults && count($results) === 0 && strlen($query) >= 3)
        <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-50">
            <div class="px-4 py-8 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="text-lg font-medium mb-2">Không tìm thấy kết quả</p>
                <p class="text-sm">Hãy thử với từ khóa khác hoặc kiểm tra chính tả</p>
            </div>
        </div>
    @endif
</div>
