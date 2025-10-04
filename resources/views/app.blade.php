@extends('layouts.app')
@section('title', 'Home Page')
@section('content')
@include('partials.slider')

<!-- Book Recommendations Slider -->
<section class="py-16 bg-background">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-foreground mb-4">Sách gợi ý cho bạn</h2>
        <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
          Những cuốn sách được chọn lọc kỹ càng dành riêng cho sở thích đọc của bạn
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($book_recommendations as $book)
          <livewire:book-card :book="$book" cardStyle="default" :key="'recommendation-' . $book->id" />
        @endforeach
      </div>

      <div class="text-center mt-12">
        <a href="{{ route('categories.recommendations') }}"
          class="border border-gray-300 text-gray-700 px-6 py-3 rounded hover:bg-gray-100 transition"
        >
          Xem thêm gợi ý
        </a>
      </div>
    </div>
  </section>

<!-- Best Sellers -->
<section class="py-16 bg-background">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-foreground mb-4">Best Sellers</h2>
        <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
          Những cuốn sách bán chạy nhất mọi thời đại được độc giả tin tưởng
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($best_sellers as $book)
          <livewire:book-card :book="$book" cardStyle="horizontal" :key="'bestseller-' . $book->id" />
        @endforeach
      </div>

      <div class="text-center mt-12">
        <a href="{{ route('categories.best-sellers') }}" class="border border-gray-300 text-gray-700 py-3 px-6 rounded hover:bg-gray-100">
          Xem tất cả Best Sellers
        </a>
      </div>
    </div>
  </section>

<!-- New Releases -->
<section class="py-16 bg-card bg-amber-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-foreground mb-4">Sách mới phát hành</h2>
        <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
          Cập nhật những đầu sách mới nhất từ các nhà xuất bản uy tín
        </p>
      </div>

      <!-- Mobile horizontal scroll, Desktop grid -->
      <div class="md:hidden">
        <div class="flex gap-4 overflow-x-auto snap-x snap-mandatory scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent scroll-px-4 pb-1">
          @foreach($new_publishers as $book)
            <div class="min-w-[260px] snap-start shrink-0 last:pr-4">
              <livewire:book-card :book="$book" cardStyle="compact" :key="'newrelease-mobile-' . $book->id" />
            </div>
          @endforeach
        </div>
      </div>

      <div class="hidden md:grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($new_publishers as $book)
          <div class="h-full">
            <livewire:book-card :book="$book" cardStyle="compact" :key="'newrelease-desktop-' . $book->id" />
          </div>
        @endforeach
      </div>

      <div class="text-center mt-12">
        <a href="{{ route('categories.new-releases') }}"
          class="inline-flex items-center justify-center gap-2 px-6 py-3 cursor-pointer rounded-md border border-input bg-white text-foreground hover:bg-amber-100 hover:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 text-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
          Xem tất cả sách mới
        </a>
      </div>
    </div>
  </section>

  <!-- Toast Notification -->
  <div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50">
    <div class="flex items-center">
      <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
      </svg>
      <span id="toast-message">Đã thêm vào giỏ hàng!</span>
    </div>
  </div>


@endsection
