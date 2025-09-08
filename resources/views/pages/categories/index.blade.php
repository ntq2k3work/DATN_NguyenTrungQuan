@extends('layouts.app')
@section('title', 'Tất cả sách')
@section('content')

<section class="py-8 sm:py-12 lg:py-16 bg-background">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-foreground mb-3 sm:mb-4">Tất cả sách</h1>
            <p class="text-muted-foreground text-base sm:text-lg max-w-2xl mx-auto px-4">
                Khám phá thế giới tri thức với hàng nghìn cuốn sách đa dạng từ mọi thể loại
            </p>
        </div>

        <div class="main-content-with-sidebar flex flex-col lg:flex-row gap-4 sm:gap-6 lg:gap-8">
            <div class="hidden lg:block flex-shrink-0">
                @include('partials.category-sidebar')
            </div>

            <div class="lg:hidden mb-4">
                <button id="mobile-filter-btn" class="w-full bg-primary text-white font-medium py-3 px-4 rounded-lg hover:bg-primary/90 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                    </svg>
                    Lọc & Tìm kiếm
                </button>
            </div>

            <div id="mobile-sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden">
                <div class="fixed inset-y-0 left-0 w-80 bg-white shadow-xl z-50 transform transition-transform duration-300 ease-in-out" id="mobile-sidebar">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-semibold">Bộ lọc</h3>
                        <button id="close-mobile-sidebar" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 overflow-y-auto max-h-[calc(100vh-80px)]">
                        @include('partials.category-sidebar')
                    </div>
                </div>
            </div>

            <div class="flex-1">
                <div id="loading-spinner" class="justify-center items-center py-8" style="display: none;">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
                    <span class="ml-2 text-gray-600">Đang tải...</span>
                </div>

                <div class="flex items-center justify-between mb-4 p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">
                            Hiển thị <span id="current-count">{{ $books->count() }}</span>
                            trong tổng số <span id="total-count">{{ $books->total() }}</span> sách
                        </p>
                    </div>

                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Sắp xếp:</label>
                        <select id="sort-select" class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:ring-purple-500 focus:border-purple-500">
                            <option value="default">Mặc định</option>
                            <option value="price_asc">Giá tăng dần</option>
                            <option value="price_desc">Giá giảm dần</option>
                            <option value="name_asc">Tên A-Z</option>
                            <option value="name_desc">Tên Z-A</option>
                            <option value="newest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                        </select>
                    </div>
                </div>

                <div id="books-grid-container">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                        @foreach ($books as $book)
                        <div class="group hover:shadow-lg transition-shadow duration-300 bg-amber-50 rounded-lg overflow-hidden">
                            <div class="p-3 sm:p-4">
                                <div class="relative mb-3 sm:mb-4">
                                    <a href="{{ route('product.show', $book->slug) }}">
                                        <img
                                            src="{{ asset($book->image_url) }}"
                                            alt="{{ $book->title }}"
                                            class="w-full h-[200px] sm:h-[220px] lg:h-[280px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300"
                                        />
                                    </a>
                                    @if($book->category)
                                    <span class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                                        {{ $book->category->name }}
                                    </span>
                                    @endif
                                    <x-wishlist-button :book="$book" :in-wishlist="false" size="sm" />
                                </div>

                                <div class="space-y-2 sm:space-y-3">
                                    <div>
                                        <h3 class="font-semibold text-foreground line-clamp-2 group-hover:text-primary transition-colors text-sm sm:text-base">
                                            <a href="{{ route('product.show', $book->slug) }}" class="hover:text-primary transition-colors">
                                                {{ $book->title }}
                                            </a>
                                        </h3>
                                        <p class="text-xs sm:text-sm text-muted-foreground">{{ $book->author?->name ?? 'Unknown Author' }}</p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center text-yellow-400">
                                            <span class="text-sm sm:text-base">★★★★★</span>
                                        </div>
                                        <span class="text-xs sm:text-sm text-muted-foreground">4.8</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-col">
                                            <span class="text-base sm:text-lg font-bold text-orange-500">{{ number_format($book->price, 0, ',', '.') }}đ</span>
                                            @if(isset($book->discount_percent) && $book->discount_percent > 0)
                                            <span class="text-xs sm:text-sm text-muted-foreground line-through text-teal-600">{{ number_format($book->price, 0, ',', '.') }}đ</span>
                                            @endif
                                        </div>
                                        <div class="flex gap-1 sm:gap-2">
                                            <button class="border border-gray-300 p-1.5 sm:p-2 rounded hover:bg-gray-100">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                            </button>
                                            <button class="bg-primary text-white px-3 sm:px-4 py-2 rounded hover:bg-primary/90 text-sm sm:text-base">Mua</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                @if($books->count() > 0)
                <div class="mt-8 sm:mt-12 flex justify-center">
                    {{ $books->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileFilterBtn = document.getElementById('mobile-filter-btn');
    const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');
    const closeMobileSidebar = document.getElementById('close-mobile-sidebar');
    const mobileSidebar = document.getElementById('mobile-sidebar');

    mobileFilterBtn.addEventListener('click', function() {
        mobileSidebarOverlay.classList.remove('hidden');
        mobileSidebar.classList.remove('-translate-x-full');
    });

    function closeSidebar() {
        mobileSidebarOverlay.classList.add('hidden');
        mobileSidebar.classList.add('-translate-x-full');
    }

    closeMobileSidebar.addEventListener('click', closeSidebar);
    mobileSidebarOverlay.addEventListener('click', function(e) {
        if (e.target === mobileSidebarOverlay) {
            closeSidebar();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSidebar();
        }
    });

    const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const loadingSpinner = document.getElementById('loading-spinner');
    const booksGridContainer = document.getElementById('books-grid-container');
    const currentCountSpan = document.getElementById('current-count');
    const totalCountSpan = document.getElementById('total-count');
    const minPriceInput = document.getElementById('min-price');
    const maxPriceInput = document.getElementById('max-price');
    const applyPriceRangeBtn = document.getElementById('apply-price-range');
    const sortSelect = document.getElementById('sort-select');

    let filterTimeout;
    let customPriceRange = { min: 0, max: 0 };

    const handleFilterChange = () => {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            performFilter();
        }, 300);
    };

    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', handleFilterChange);
    });

    sortSelect.addEventListener('change', () => {
        performFilter();
    });

    applyPriceRangeBtn.addEventListener('click', () => {
        const minPrice = parseInt(minPriceInput.value) || 0;
        const maxPrice = parseInt(maxPriceInput.value) || 0;

        if (minPrice > 0 || maxPrice > 0) {
            customPriceRange = { min: minPrice, max: maxPrice };
            document.querySelectorAll('.price-checkbox').forEach(cb => {
                cb.checked = false;
            });
            performFilter();
        }
    });

    clearFiltersBtn.addEventListener('click', () => {
        filterCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        minPriceInput.value = '';
        maxPriceInput.value = '';
        customPriceRange = { min: 0, max: 0 };
        performFilter();
    });

    const performFilter = () => {
        const selectedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'))
            .map(checkbox => checkbox.value);

        const selectedPublishers = Array.from(document.querySelectorAll('.publisher-checkbox:checked'))
            .map(checkbox => checkbox.value);

        const selectedPriceRanges = Array.from(document.querySelectorAll('.price-checkbox:checked'))
            .map(checkbox => checkbox.value);

        const selectedSort = sortSelect.value;

        loadingSpinner.style.display = 'flex';
        booksGridContainer.style.opacity = '0.5';

        const params = new URLSearchParams();
        if (selectedCategories.length > 0) {
            selectedCategories.forEach(category => {
                params.append('categories[]', category);
            });
        }
        if (selectedPublishers.length > 0) {
            selectedPublishers.forEach(publisher => {
                params.append('publishers[]', publisher);
            });
        }
        if (selectedPriceRanges.length > 0) {
            selectedPriceRanges.forEach(range => {
                params.append('price_ranges[]', range);
            });
        }
        if (customPriceRange.min > 0) {
            params.append('custom_price_min', customPriceRange.min);
        }
        if (customPriceRange.max > 0) {
            params.append('custom_price_max', customPriceRange.max);
        }
        if (selectedSort && selectedSort !== 'default') {
            params.append('sort', selectedSort);
        }

        fetch(`/api/categories/filter?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            booksGridContainer.innerHTML = `
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                    ${data.html}
                </div>
            `;

            currentCountSpan.textContent = data.total;
            totalCountSpan.textContent = data.total;

            loadingSpinner.style.display = 'none';
            booksGridContainer.style.opacity = '1';

            const url = new URL(window.location);
            if (selectedCategories.length > 0) {
                url.searchParams.set('categories', selectedCategories.join(','));
            } else {
                url.searchParams.delete('categories');
            }
            if (selectedPublishers.length > 0) {
                url.searchParams.set('publishers', selectedPublishers.join(','));
            } else {
                url.searchParams.delete('publishers');
            }
            if (selectedPriceRanges.length > 0) {
                url.searchParams.set('price_ranges', selectedPriceRanges.join(','));
            } else {
                url.searchParams.delete('price_ranges');
            }
            if (customPriceRange.min > 0) {
                url.searchParams.set('custom_price_min', customPriceRange.min);
            } else {
                url.searchParams.delete('custom_price_min');
            }
            if (customPriceRange.max > 0) {
                url.searchParams.set('custom_price_max', customPriceRange.max);
            } else {
                url.searchParams.delete('custom_price_max');
            }
            if (selectedSort && selectedSort !== 'default') {
                url.searchParams.set('sort', selectedSort);
            } else {
                url.searchParams.delete('sort');
            }
            window.history.pushState({}, '', url);
        })
        .catch(error => {
            console.error('Error:', error);
            loadingSpinner.style.display = 'none';
            booksGridContainer.style.opacity = '1';

            let errorMessage = 'Có lỗi xảy ra khi tải dữ liệu. Vui lòng thử lại.';
            if (error.response && error.response.json) {
                error.response.json().then(data => {
                    console.error('Server error:', data);
                    if (data.error) {
                        errorMessage = data.error;
                    }
                });
            }

            booksGridContainer.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-red-500">
                        <svg class="mx-auto h-8 w-8 text-red-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <p>${errorMessage}</p>
                    </div>
                </div>
            `;
        });
    };

    const initializeFiltersFromURL = () => {
        const urlParams = new URLSearchParams(window.location.search);

        const categoriesParam = urlParams.get('categories');
        if (categoriesParam) {
            const categories = categoriesParam.split(',');
            categories.forEach(categoryId => {
                const checkbox = document.querySelector(`.category-checkbox[value="${categoryId}"]`);
                if (checkbox) checkbox.checked = true;
            });
        }

        const publishersParam = urlParams.get('publishers');
        if (publishersParam) {
            const publishers = publishersParam.split(',');
            publishers.forEach(publisherId => {
                const checkbox = document.querySelector(`.publisher-checkbox[value="${publisherId}"]`);
                if (checkbox) checkbox.checked = true;
            });
        }

        const priceRangesParam = urlParams.get('price_ranges');
        if (priceRangesParam) {
            const priceRanges = priceRangesParam.split(',');
            priceRanges.forEach(range => {
                const checkbox = document.querySelector(`.price-checkbox[value="${range}"]`);
                if (checkbox) checkbox.checked = true;
            });
        }

        const customPriceMin = urlParams.get('custom_price_min');
        const customPriceMax = urlParams.get('custom_price_max');
        if (customPriceMin || customPriceMax) {
            minPriceInput.value = customPriceMin || '';
            maxPriceInput.value = customPriceMax || '';
            customPriceRange = {
                min: parseInt(customPriceMin) || 0,
                max: parseInt(customPriceMax) || 0
            };
        }

        const sortParam = urlParams.get('sort');
        if (sortParam) {
            sortSelect.value = sortParam;
        }

        if (categoriesParam || publishersParam || priceRangesParam || customPriceMin || customPriceMax || sortParam) {
            performFilter();
        }
    };

    initializeFiltersFromURL();
});
</script>

@endsection
