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
        <!-- Sách gợi ý -->
        @foreach ($book_recommendations as $book)
        <div class="group bg-amber-50 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
          <div class="p-4">
            <div class="relative mb-4">
              <img
                src="{{ asset($book->image_url) }}"
                alt="{{ $book->title }}"
                class="w-full h-[280px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300"
              />
              <span class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">Bestseller</span>
              <button
                class="absolute top-2 right-2 bg-white/80 hover:bg-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                  />
                </svg>
              </button>
            </div>

            <div class="space-y-3">
              <div>
                <h3 class="font-semibold text-foreground line-clamp-2 group-hover:text-primary transition-colors">
                  {{ $book->title }}
                </h3>
                <p class="text-sm text-gray-500">{{ $book->author->name }}</p>
              </div>

              <div class="flex items-center gap-2">
                <div class="flex items-center text-yellow-400">
                  <span>★★★★★</span>
                </div>
                <span class="text-sm text-gray-500">4.8 (1234)</span>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  @if ($book->discount_percent)
                    <span class="text-lg font-bold text-primary">{{ number_format($book->discount_price, 0, ',', '.') }}đ</span>
                    <span class="text-sm text-gray-500 line-through">{{ number_format($book->price, 0, ',', '.') }}đ</span>
                  @else
                    <span class="text-lg font-bold text-primary">{{ number_format($book->price, 0, ',', '.') }}đ</span>
                  @endif
                </div>
              </div>

              <button
                class="w-full bg-amber-600 text-white text-sm py-2 px-4 rounded hover:bg-primary/90 transition"
              >
                Thêm vào giỏ
              </button>
            </div>
          </div>
        </div>
        @endforeach


      </div>

      <div class="text-center mt-12">
        <a href="{{ route('categories.recommendations') }}"
          class="border hover:bg-sky-50 border-gray-300 text-gray-700 px-6 py-3 rounded hover:bg-gray-100 transition"
        >
          Xem thêm gợi ý
        </a>
      </div>
    </div>
  </section>

{{-- Top bán chạy --}}
  <section class="py-16 bg-teal-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-foreground mb-4">Top sách bán chạy tháng này</h2>
        <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
          Những cuốn sách được độc giả yêu thích và mua nhiều nhất trong tháng
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">

        <!-- Book Card 1 -->
        <div class="group hover:shadow-lg transition-shadow duration-300 relative bg-amber-50 rounded-lg overflow-hidden">
          <div class="p-4">
            <!-- Rank Badge -->
            <div class="absolute -top-3 -left-3 z-10">
              <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm bg-yellow-500">
                1
              </div>
            </div>

            <div class="relative mb-4">
              <img
                src="{{ asset('images/atomic-habits-inspired-cover.png') }}"
                alt="Atomic Habits"
                class="w-full h-[240px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300"
              />
              <button class="absolute top-2 right-2 bg-background/80 hover:bg-background p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                  />
                </svg>
              </button>
            </div>

            <div class="space-y-3">
              <div>
                <h3 class="font-semibold text-foreground line-clamp-2 group-hover:text-primary transition-colors text-sm">
                  Atomic Habits
                </h3>
                <p class="text-xs text-muted-foreground">James Clear</p>
              </div>

              <div class="flex items-center gap-1">
                <span class="inline-block bg-orange-400 text-xs px-2 py-1 rounded">Đã bán 1250</span>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex flex-col">
                  <span class="text-sm font-bold text-yellow-600">167.000đ</span>
                  <span class="text-xs text-teal-600 line-through">209.000đ</span>
                </div>
              </div>

              <button class="w-full bg-amber-600 text-white font-medium py-2 px-4 rounded hover:bg-primary/90">
                Mua ngay
              </button>
            </div>
          </div>
        </div>

        <!-- Lặp lại các block sách tương tự cho sách khác -->
      </div>

      <div class="text-center mt-12">
        <a href="{{ route('categories.top-selling') }}" class="border hover:bg-sky-50 border-gray-300 text-gray-700 py-3 px-6 rounded hover:bg-gray-100">
          Xem tất cả top bán chạy
        </a>
      </div>
    </div>
  </section>


<!-- Best Sellers -->
<section class="py-16 bg-background">
    <div class="container mx-auto px-4">
      <!-- Header -->
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-foreground mb-4">Best Sellers</h2>
        <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
          Những cuốn sách bán chạy nhất mọi thời đại được độc giả tin tưởng
        </p>
      </div>

      <!-- Category filters -->
      {{-- <div class="flex flex-wrap justify-center gap-4 mb-12">
        <button class="px-6 bg-amber-600 text-white rounded hover:bg-amber-500 hover:text-white">Tất cả</button>
        <button class="px-6 border border-gray-300 rounded hover:bg-amber-500 hover:text-white">Văn học</button>
        <button class="px-6 border border-gray-300 rounded hover:bg-amber-500 hover:text-white">Kinh doanh</button>
        <button class="px-6 border border-gray-300 rounded hover:bg-amber-500 hover:text-white">Kỹ năng sống</button>
        <button class="px-6 border border-gray-300 rounded hover:bg-amber-500 hover:text-white">Khoa học</button>
      </div> --}}

      <!-- Books Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Book Card 1 -->
        @foreach ($best_sellers as $book )
          <div class="group hover:shadow-lg transition-shadow duration-300 bg-amber-50 rounded-lg overflow-hidden">
          <div class="p-6">
            <div class="flex gap-4">
              <div class="relative flex-shrink-0">
                <img
                  src="{{ asset($book->image_url) }}"
                  alt="{{ $book->title }}"
                  class="w-24 h-32 object-cover rounded-md group-hover:scale-105 transition-transform duration-300"
                />
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">{{ $book->percent ? $book->percent.'%' : number_format($book->amount,0,',','.').'đ' }}</span>
              </div>
              <div class="flex-1 space-y-3">
                <div>
                  <h3 class="font-semibold text-foreground line-clamp-2 group-hover:text-primary transition-colors">
                    {{ $book->title }}
                  </h3>
                  <p class="text-sm text-muted-foreground">{{ $book->author->name }}</p>
                </div>
                <div class="flex items-center gap-2">
                  <div class="flex items-center text-yellow-400">
                    ★★★★★
                  </div>
                  <span class="text-sm text-muted-foreground">4.8</span>
                </div>
                <div class="flex items-center justify-between">
                  <div class="flex flex-col">
                    <span class="text-lg font-bold text-orange-500">{{ number_format($book->final_price,0,',','.') }}đ</span>
                    <span class="text-sm text-muted-foreground line-through text-teal-600">{{ number_format($book->price,0,',','.') }}</span>
                  </div>
                  <div class="flex gap-2">
                    <button class="border border-gray-300 p-2 rounded hover:bg-gray-100">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                        />
                      </svg>
                    </button>
                    <button class="bg-primary text-white px-4 py-2 rounded hover:bg-primary/90">Mua</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
        <!-- Tương tự cho các sách còn lại -->
      </div>

      <div class="text-center mt-12">
        <a href="{{ route('categories.best-sellers') }}" class="border border-gray-300 hover:bg-sky-50 text-gray-700 py-3 px-6 rounded hover:bg-gray-100">
          Xem tất cả Best Sellers
        </a>
      </div>
    </div>
  </section>


<!-- New Releases -->
<section class="py-16 bg-card bg-amber-50">
    <div class="container mx-auto px-4">
      <!-- Tiêu đề -->
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-foreground mb-4">Sách mới phát hành</h2>
        <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
          Cập nhật những đầu sách mới nhất từ các nhà xuất bản uy tín
        </p>
      </div>

      <!-- Danh sách sách -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ">
        <!-- Card 1 -->
        @foreach($new_publishers as $book)
        <div class="group hover:shadow-lg transition-shadow duration-300 bg-amber-50 rounded-lg overflow-hidden">
          <div class="p-4">
            <div class="relative mb-4">
              <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}"
                class="w-full h-[280px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300" />
              <div class="absolute top-2 left-2 flex flex-col gap-2">
                <span class="bg-secondary text-secondary-foreground px-2 py-1 text-xs rounded">Kinh doanh</span>
              </div>
              <button
                class="absolute top-2 right-2 bg-background/80 hover:bg-background p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
              </button>
            </div>

            <div class="space-y-3">
              <div>
                <h3 class="font-semibold text-foreground line-clamp-2 group-hover:text-primary transition-colors">
                  {{ $book->title }}
                </h3>
                <p class="text-sm text-muted-foreground">{{ $book->author->name }}</p>
              </div>

              <div class="flex items-center gap-2 text-sm text-muted-foreground">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Phát hành: {{ date_format($book->created_at,'d/m/Y') }}</span>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="text-lg font-bold text-primary text-orange-500">{{ $book->final_price }}đ</span>
                  <span class="text-sm text-muted-foreground line-through text-teal-600">{{ $book->price }}đ</span>
                </div>
              </div>

              <button class="w-full px-4 py-2 rounded bg-amber-600 text-white cursor-pointer hover:bg-amber-500 text-sm">
                Thêm vào giỏ
              </button>
            </div>
          </div>
        </div>
        @endforeach

        <!-- Lặp lại cho các sách khác, thay đổi nội dung -->

      </div>

      <!-- Nút xem tất cả -->
      <div class="text-center mt-12">
        <a href="{{ route('categories.new-releases') }}"
          class="px-6 py-3 cursor-pointer rounded border border-input bg-white hover:bg-sky-50 hover:bg-accent hover:text-accent-foreground text-sm">
          Xem tất cả sách mới
        </a>
      </div>
    </div>
  </section>
@endsection
