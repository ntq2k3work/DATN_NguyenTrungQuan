@extends('layouts.app')

@section('title', 'Kết quả tìm kiếm - BookStore')

@section('content')
<div class="search-results-container min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Kết quả tìm kiếm</h1>
            <p class="text-gray-600">
                @if($query)
                    Tìm kiếm cho: <span class="font-semibold text-red-600">"{{ $query }}"</span>
                @else
                    Vui lòng nhập từ khóa tìm kiếm
                @endif
            </p>
        </div>

        @if($query)
            <!-- Filters -->
            <div class="filter-section rounded-lg p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Bộ lọc</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                        <select id="category-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-red-500">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Author Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tác giả</label>
                        <select id="author-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-red-500">
                            <option value="">Tất cả tác giả</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Publisher Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nhà xuất bản</label>
                        <select id="publisher-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-red-500">
                            <option value="">Tất cả nhà xuất bản</option>
                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher->id }}" {{ request('publisher') == $publisher->id ? 'selected' : '' }}>
                                    {{ $publisher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Khoảng giá</label>
                        <select id="price-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-red-500">
                            <option value="">Tất cả mức giá</option>
                            <option value="0-50000" {{ request('price') == '0-50000' ? 'selected' : '' }}>Dưới 50.000đ</option>
                            <option value="50000-100000" {{ request('price') == '50000-100000' ? 'selected' : '' }}>50.000đ - 100.000đ</option>
                            <option value="100000-200000" {{ request('price') == '100000-200000' ? 'selected' : '' }}>100.000đ - 200.000đ</option>
                            <option value="200000-500000" {{ request('price') == '200000-500000' ? 'selected' : '' }}>200.000đ - 500.000đ</option>
                            <option value="500000-" {{ request('price') == '500000-' ? 'selected' : '' }}>Trên 500.000đ</option>
                        </select>
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <label class="text-sm font-medium text-gray-700">Sắp xếp theo:</label>
                        <select id="sort-filter" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-red-500">
                            <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Liên quan nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                        </select>
                    </div>
                    <button id="clear-filters" class="text-sm text-red-600 hover:text-red-700 font-medium">
                        Xóa bộ lọc
                    </button>
                </div>
            </div>

            <!-- Results -->
            <div class="flex items-center justify-between mb-6">
                <div class="text-sm text-gray-600">
                    Hiển thị <span class="font-semibold">{{ $books->count() }}</span>
                    trong tổng số <span class="font-semibold">{{ $totalResults }}</span> kết quả
                </div>
            </div>

            @if($books->count() > 0)
                <!-- Products Grid -->
                <div class="product-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($books as $book)
                        <div class="product-card bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <a href="{{ route('product.show', $book->slug) }}" class="block">
                                <div class="aspect-w-3 aspect-h-4">
                                    <img src="{{ $book->image_url ? (str_starts_with($book->image_url, 'http') ? $book->image_url : '/storage/' . ltrim($book->image_url, '/')) : '/images/placeholder.jpg' }}"
                                         alt="{{ $book->title }}"
                                         class="w-full h-48 object-cover">
                                </div>
                            </a>

                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                    <a href="{{ route('product.show', $book->slug) }}" class="hover:text-red-600">
                                        {{ $book->title }}
                                    </a>
                                </h3>

                                <div class="text-sm text-gray-600 mb-2">
                                    <div class="mb-1">
                                        <span class="font-medium">Tác giả:</span> {{ $book->author->name ?? 'N/A' }}
                                    </div>
                                    <div class="mb-1">
                                        <span class="font-medium">NXB:</span> {{ $book->publisher->name ?? 'N/A' }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Danh mục:</span> {{ $book->category->name ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center space-x-2">
                                        @if($book->discount)
                                            <span class="price-discount text-lg font-bold">
                                                {{ number_format($book->price * (1 - $book->discount->percent / 100), 0, ',', '.') }}đ
                                            </span>
                                            <span class="price-original text-sm">
                                                {{ number_format($book->price, 0, ',', '.') }}đ
                                            </span>
                                            <span class="discount-badge text-xs px-2 py-1 rounded">
                                                -{{ $book->discount->percent }}%
                                            </span>
                                        @else
                                            <span class="text-lg font-bold text-gray-900">
                                                {{ number_format($book->price, 0, ',', '.') }}đ
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3 flex space-x-2">
                                    <button onclick="addToCart({{ $book->id }})"
                                            class="btn-primary flex-1 text-white px-3 py-2 rounded-lg text-sm">
                                        Thêm vào giỏ
                                    </button>
                                    <button onclick="toggleWishlist({{ $book->id }})"
                                            class="btn-secondary px-3 py-2 border border-gray-300 rounded-lg">
                                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($books->hasPages())
                    <div class="flex justify-center">
                        {{ $books->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- No Results -->
                <div class="empty-state bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Không tìm thấy kết quả</h3>
                    <p class="text-gray-600 mb-6">
                        Không có sản phẩm nào phù hợp với từ khóa "{{ $query }}" và bộ lọc hiện tại.
                    </p>
                    <div class="space-x-4">
                        <button onclick="clearFilters()" class="btn-primary px-6 py-2 rounded-lg">
                            Xóa bộ lọc
                        </button>
                        <a href="{{ route('home') }}" class="btn-secondary px-6 py-2 rounded-lg">
                            Về trang chủ
                        </a>
                    </div>
                </div>
            @endif
        @else
            <!-- No Query -->
            <div class="empty-state bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Nhập từ khóa tìm kiếm</h3>
                <p class="text-gray-600 mb-6">
                    Sử dụng ô tìm kiếm ở trên để tìm kiếm sản phẩm theo tên, tác giả, nhà xuất bản hoặc danh mục.
                </p>
                <a href="{{ route('home') }}" class="btn-primary px-6 py-2 rounded-lg">
                    Về trang chủ
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filters = ['category-filter', 'author-filter', 'publisher-filter', 'price-filter', 'sort-filter'];

    filters.forEach(filterId => {
        const filter = document.getElementById(filterId);
        if (filter) {
            filter.addEventListener('change', function() {
                applyFilters();
            });
        }
    });

    const clearFiltersBtn = document.getElementById('clear-filters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', clearFilters);
    }
});

function applyFilters() {
    const url = new URL(window.location);
    const params = new URLSearchParams();

    // Get current query
    const query = url.searchParams.get('q');
    if (query) {
        params.set('q', query);
    }

    // Get filter values
    const category = document.getElementById('category-filter')?.value;
    const author = document.getElementById('author-filter')?.value;
    const publisher = document.getElementById('publisher-filter')?.value;
    const price = document.getElementById('price-filter')?.value;
    const sort = document.getElementById('sort-filter')?.value;

    if (category) params.set('category', category);
    if (author) params.set('author', author);
    if (publisher) params.set('publisher', publisher);
    if (price) params.set('price', price);
    if (sort) params.set('sort', sort);

    // Redirect with new parameters
    window.location.href = '{{ route('search.results') }}' + '?' + params.toString();
}

function clearFilters() {
    const url = new URL(window.location);
    const query = url.searchParams.get('q');

    if (query) {
        window.location.href = '{{ route('search.results') }}?q=' + encodeURIComponent(query);
    } else {
        window.location.href = '{{ route('search.results') }}';
    }
}

// Cart and Wishlist functions (assuming they exist globally)
function addToCart(bookId) {
    // Implementation depends on existing cart functionality
    console.log('Add to cart:', bookId);
}

function toggleWishlist(bookId) {
    // Implementation depends on existing wishlist functionality
    console.log('Toggle wishlist:', bookId);
}
</script>
@endpush
