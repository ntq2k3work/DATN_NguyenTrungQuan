@extends('layouts.app')
@section('title', 'Top sách bán chạy')
@section('content')

<section class="py-8 sm:py-12 lg:py-16 bg-background">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8 sm:mb-12">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-foreground mb-3 sm:mb-4">Top sách bán chạy tháng này</h1>
            <p class="text-muted-foreground text-base sm:text-lg max-w-2xl mx-auto px-4">
                Những cuốn sách được độc giả yêu thích và mua nhiều nhất trong tháng
            </p>
        </div>

        <!-- Main Content with Sidebar -->
        <div class="main-content-with-sidebar flex flex-col lg:flex-row gap-4 sm:gap-6 lg:gap-8">
            <!-- Left Sidebar - Hidden on mobile, shown on larger screens -->
            <div class="hidden lg:block flex-shrink-0 sticky top-0 self-start">
                @include('partials.category-sidebar')
            </div>

            <!-- Mobile Filter Button -->
            <div class="lg:hidden mb-4">
                <button id="mobile-filter-btn" class="w-full bg-primary text-white font-medium py-3 px-4 rounded-lg hover:bg-primary/90 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                    </svg>
                    Lọc & Tìm kiếm
                </button>
            </div>

            <!-- Mobile Sidebar Overlay -->
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

            <!-- Right Content - Books Grid -->
            <div class="flex-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            @foreach ($books as $index => $book)
            <div class="group hover:shadow-lg transition-shadow duration-300 relative bg-amber-50 rounded-lg overflow-hidden">
                <div class="p-3 sm:p-4">
                    <!-- Rank Badge -->
                    <div class="absolute -top-2 -left-2 sm:-top-3 sm:-left-3 z-10">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-white font-bold text-xs sm:text-sm
                            {{ $index < 3 ? 'bg-yellow-500' : 'bg-gray-500' }}">
                            {{ $index + 1 }}
                        </div>
                    </div>

                    <div class="relative mb-3 sm:mb-4">
                        <img
                            src="{{ asset($book->image_url) }}"
                            alt="{{ $book->title }}"
                            class="w-full h-[180px] sm:h-[200px] lg:h-[240px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300"
                        />
                        <button class="absolute top-2 right-2 bg-background/80 hover:bg-background p-1.5 sm:p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-2 sm:space-y-3">
                        <div>
                            <h3 class="font-semibold text-foreground line-clamp-2 group-hover:text-primary transition-colors text-sm sm:text-base">
                                {{ $book->title }}
                            </h3>
                            <p class="text-xs sm:text-sm text-muted-foreground">{{ $book->author?->name ?? 'Unknown' }}</p>
                        </div>

                        <div class="flex items-center gap-1">
                            <span class="inline-block bg-orange-400 text-white text-xs px-2 py-1 rounded">Đã bán {{ $book->sales_count ?? rand(100, 2000) }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-sm sm:text-base font-bold text-yellow-600">{{ number_format($book->final_price ?? $book->price, 0, ',', '.') }}đ</span>
                                @if(isset($book->discount_percent) && $book->discount_percent > 0)
                                <span class="text-xs sm:text-sm text-teal-600 line-through">{{ number_format($book->price, 0, ',', '.') }}đ</span>
                                @endif
                            </div>
                        </div>

                        <button class="w-full bg-amber-600 text-white font-medium py-2 px-3 sm:px-4 rounded hover:bg-primary/90 text-sm sm:text-base">
                            Mua ngay
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
                </div>

                <!-- Pagination -->
                @if($books->count() > 0)
                <div class="mt-8 sm:mt-12">
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

    // Open mobile sidebar
    mobileFilterBtn.addEventListener('click', function() {
        mobileSidebarOverlay.classList.remove('hidden');
        mobileSidebar.classList.remove('-translate-x-full');
    });

    // Close mobile sidebar
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

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSidebar();
        }
    });
});
</script>

@endsection
