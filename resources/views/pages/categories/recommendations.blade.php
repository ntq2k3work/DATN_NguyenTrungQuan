@extends('layouts.app')
@section('title', 'Sách gợi ý cho bạn')
@section('content')

<section class="py-8 sm:py-12 lg:py-16 bg-background">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8 sm:mb-12">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-foreground mb-3 sm:mb-4">Sách gợi ý cho bạn</h1>
            <p class="text-muted-foreground text-base sm:text-lg max-w-2xl mx-auto px-4">
                Những cuốn sách được chọn lọc kỹ càng dành riêng cho sở thích đọc của bạn
            </p>
        </div>

        <div class="main-content-with-sidebar flex flex-col lg:flex-row gap-4 sm:gap-6 lg:gap-8">
            <div class="hidden lg:block flex-shrink-0">
                @livewire('category-sidebar')
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
                        @livewire('category-sidebar')
                    </div>
                </div>
            </div>

            <div class="flex-1">
                @livewire('category-books')
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
});
</script>

@endsection
