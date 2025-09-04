<div class="category-sidebar w-full lg:w-64 bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6" data-category-slug="{{ $category->slug ?? '' }}">
    <!-- Danh mục sản phẩm -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-900 text-base lg:text-lg">Danh mục sản phẩm</h3>
            <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="toggleSection('categories')">
                <svg class="w-4 h-4 lg:w-5 lg:h-5 toggle-icon" id="categories-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>
        <div class="w-full h-0.5 bg-purple-500 mb-4"></div>
        <div id="categories-content" class="section-content space-y-2">
            @if(isset($categories) && $categories->count() > 0)
                @foreach($categories as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" class="block text-gray-700 hover:text-purple-600 transition-colors py-1 text-sm lg:text-base">
                        {{ $cat->name }}
                    </a>
                @endforeach
            @else
                <p class="text-gray-500 text-sm">Không có danh mục nào</p>
            @endif
        </div>
    </div>

    <!-- Nhà xuất bản -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-900 text-base lg:text-lg">Nhà xuất bản</h3>
            <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="toggleSection('publishers')">
                <svg class="w-4 h-4 lg:w-5 lg:h-5 toggle-icon" id="publishers-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>
        <div class="w-full h-0.5 bg-purple-500 mb-4"></div>
        <div id="publishers-content" class="section-content space-y-3">
            @if(isset($publishers) && $publishers->count() > 0)
                @foreach($publishers as $publisher)
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="publishers[]" value="{{ $publisher->id }}" class="filter-checkbox publisher-checkbox w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-gray-700 text-sm lg:text-base">{{ $publisher->name }}</span>
                    </label>
                @endforeach
            @else
                <p class="text-gray-500 text-sm">Không có nhà xuất bản nào</p>
            @endif
        </div>
    </div>

    <!-- Lọc giá -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-900 text-base lg:text-lg">Lọc giá</h3>
            <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="toggleSection('price')">
                <svg class="w-4 h-4 lg:w-5 lg:h-5 toggle-icon" id="price-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>
        <div class="w-full h-0.5 bg-purple-500 mb-4"></div>
        <div id="price-content" class="section-content space-y-3">
            <!-- Price Range Slider -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Khoảng giá (₫)</label>
                <div class="flex items-center space-x-2 mb-2">
                    <input type="number" id="min-price" placeholder="Min" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-purple-500 focus:border-purple-500">
                    <span class="text-gray-500">-</span>
                    <input type="number" id="max-price" placeholder="Max" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-purple-500 focus:border-purple-500">
                </div>
                <button id="apply-price-range" class="w-full bg-purple-600 text-white text-xs py-1 px-2 rounded hover:bg-purple-700 transition-colors">
                    Áp dụng
                </button>
            </div>

            <!-- Quick Price Filters -->
            <div class="space-y-2">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="price_range[]" value="0-100000" class="filter-checkbox price-checkbox w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <span class="text-gray-700 text-sm lg:text-base">Dưới 100.000₫</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="price_range[]" value="100000-300000" class="filter-checkbox price-checkbox w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <span class="text-gray-700 text-sm lg:text-base">100.000₫ - 300.000₫</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="price_range[]" value="300000-500000" class="filter-checkbox price-checkbox w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <span class="text-gray-700 text-sm lg:text-base">300.000₫ - 500.000₫</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="price_range[]" value="500000-1000000" class="filter-checkbox price-checkbox w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <span class="text-gray-700 text-sm lg:text-base">500.000₫ - 1.000.000₫</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="price_range[]" value="1000000-999999999" class="filter-checkbox price-checkbox w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <span class="text-gray-700 text-sm lg:text-base">Trên 1.000.000₫</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Clear Filters Button -->
    <div class="mb-6">
        <button id="clear-filters" class="w-full bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors text-sm">
            Xóa bộ lọc
        </button>
    </div>

    <script>
        function toggleSection(sectionName) {
            const content = document.getElementById(sectionName + '-content');
            const icon = document.getElementById(sectionName + '-icon');

            if (content.classList.contains('collapsed')) {
                content.classList.remove('collapsed');
                icon.classList.remove('rotated');
            } else {
                content.classList.add('collapsed');
                icon.classList.add('rotated');
            }
        }
    </script>
</div>
