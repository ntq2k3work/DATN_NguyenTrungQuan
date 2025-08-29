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
        <!-- Sách -->
        <div class="group bg-amber-50 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
          <div class="p-4">
            <div class="relative mb-4">
              <img
                src="{{ asset('images/the-alchemist-book-cover.png') }}"
                alt="Nhà Giả Kim"
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
                  Nhà Giả Kim
                </h3>
                <p class="text-sm text-gray-500">Paulo Coelho</p>
              </div>

              <div class="flex items-center gap-2">
                <div class="flex items-center text-yellow-400">
                  <span>★★★★★</span>
                </div>
                <span class="text-sm text-gray-500">4.8 (1234)</span>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="text-lg font-bold text-primary">89.000đ</span>
                  <span class="text-sm text-gray-500 line-through">120.000đ</span>
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
        <div class="group bg-amber-50 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
          <div class="p-4">
            <div class="relative mb-4">
              <img
                src="{{ asset('images/the-alchemist-book-cover.png') }}"
                alt="Nhà Giả Kim"
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
                  Nhà Giả Kim
                </h3>
                <p class="text-sm text-gray-500">Paulo Coelho</p>
              </div>

              <div class="flex items-center gap-2">
                <div class="flex items-center text-yellow-400">
                  <span>★★★★★</span>
                </div>
                <span class="text-sm text-gray-500">4.8 (1234)</span>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="text-lg font-bold text-primary">89.000đ</span>
                  <span class="text-sm text-gray-500 line-through">120.000đ</span>
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
        <div class="group bg-amber-50 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
          <div class="p-4">
            <div class="relative mb-4">
              <img
                src="{{ asset('images/the-alchemist-book-cover.png') }}"
                alt="Nhà Giả Kim"
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
                  Nhà Giả Kim
                </h3>
                <p class="text-sm text-gray-500">Paulo Coelho</p>
              </div>

              <div class="flex items-center gap-2">
                <div class="flex items-center text-yellow-400">
                  <span>★★★★★</span>
                </div>
                <span class="text-sm text-gray-500">4.8 (1234)</span>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="text-lg font-bold text-primary">89.000đ</span>
                  <span class="text-sm text-gray-500 line-through">120.000đ</span>
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
        <div class="group bg-amber-50 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
          <div class="p-4">
            <div class="relative mb-4">
              <img
                src="{{ asset('images/the-alchemist-book-cover.png') }}"
                alt="Nhà Giả Kim"
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
                  Nhà Giả Kim
                </h3>
                <p class="text-sm text-gray-500">Paulo Coelho</p>
              </div>

              <div class="flex items-center gap-2">
                <div class="flex items-center text-yellow-400">
                  <span>★★★★★</span>
                </div>
                <span class="text-sm text-gray-500">4.8 (1234)</span>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="text-lg font-bold text-primary">89.000đ</span>
                  <span class="text-sm text-gray-500 line-through">120.000đ</span>
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
        <div class="group bg-amber-50 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
          <div class="p-4">
            <div class="relative mb-4">
              <img
                src="{{ asset('images/the-alchemist-book-cover.png') }}"
                alt="Nhà Giả Kim"
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
                  Nhà Giả Kim
                </h3>
                <p class="text-sm text-gray-500">Paulo Coelho</p>
              </div>

              <div class="flex items-center gap-2">
                <div class="flex items-center text-yellow-400">
                  <span>★★★★★</span>
                </div>
                <span class="text-sm text-gray-500">4.8 (1234)</span>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="text-lg font-bold text-primary">89.000đ</span>
                  <span class="text-sm text-gray-500 line-through">120.000đ</span>
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
        <div class="group bg-amber-50 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
          <div class="p-4">
            <div class="relative mb-4">
              <img
                src="{{ asset('images/the-alchemist-book-cover.png') }}"
                alt="Nhà Giả Kim"
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
                  Nhà Giả Kim
                </h3>
                <p class="text-sm text-gray-500">Paulo Coelho</p>
              </div>

              <div class="flex items-center gap-2">
                <div class="flex items-center text-yellow-400">
                  <span>★★★★★</span>
                </div>
                <span class="text-sm text-gray-500">4.8 (1234)</span>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="text-lg font-bold text-primary">89.000đ</span>
                  <span class="text-sm text-gray-500 line-through">120.000đ</span>
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
        <div class="group bg-amber-50 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
          <div class="p-4">
            <div class="relative mb-4">
              <img
                src="{{ asset('images/the-alchemist-book-cover.png') }}"
                alt="Nhà Giả Kim"
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
                  Nhà Giả Kim
                </h3>
                <p class="text-sm text-gray-500">Paulo Coelho</p>
              </div>

              <div class="flex items-center gap-2">
                <div class="flex items-center text-yellow-400">
                  <span>★★★★★</span>
                </div>
                <span class="text-sm text-gray-500">4.8 (1234)</span>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="text-lg font-bold text-primary">89.000đ</span>
                  <span class="text-sm text-gray-500 line-through">120.000đ</span>
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
        <div class="group bg-amber-50 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
          <div class="p-4">
            <div class="relative mb-4">
              <img
                src="{{ asset('images/the-alchemist-book-cover.png') }}"
                alt="Nhà Giả Kim"
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
                  Nhà Giả Kim
                </h3>
                <p class="text-sm text-gray-500">Paulo Coelho</p>
              </div>

              <div class="flex items-center gap-2">
                <div class="flex items-center text-yellow-400">
                  <span>★★★★★</span>
                </div>
                <span class="text-sm text-gray-500">4.8 (1234)</span>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="text-lg font-bold text-primary">89.000đ</span>
                  <span class="text-sm text-gray-500 line-through">120.000đ</span>
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
      </div>

      <div class="text-center mt-12">
        <button
          class="border border-gray-300 text-gray-700 px-6 py-3 rounded hover:bg-gray-100 transition"
        >
          Xem thêm gợi ý
        </button>
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
        <button class="border border-gray-300 text-gray-700 py-3 px-6 rounded hover:bg-gray-100">
          Xem tất cả top bán chạy
        </button>
      </div>
    </div>
  </section>


        <!-- Best Sellers -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-warm-gray-900 mb-4">Best Sellers</h2>
                    <p class="text-warm-gray-600">Những cuốn sách bán chạy nhất mọi thời đại</p>
                </div>

                <!-- Category Filter -->
                <div class="flex flex-wrap justify-center gap-4 mb-8">
                    <button onclick="filterBestSellers('all')" class="filter-btn active bg-terracotta-600 text-white px-6 py-2 rounded-full font-medium hover:bg-terracotta-700 transition-colors">
                        Tất cả
                    </button>
                    <button onclick="filterBestSellers('fiction')" class="filter-btn bg-sage-100 text-warm-gray-700 px-6 py-2 rounded-full font-medium hover:bg-sage-200 transition-colors">
                        Tiểu thuyết
                    </button>
                    <button onclick="filterBestSellers('business')" class="filter-btn bg-sage-100 text-warm-gray-700 px-6 py-2 rounded-full font-medium hover:bg-sage-200 transition-colors">
                        Kinh doanh
                    </button>
                    <button onclick="filterBestSellers('self-help')" class="filter-btn bg-sage-100 text-warm-gray-700 px-6 py-2 rounded-full font-medium hover:bg-sage-200 transition-colors">
                        Phát triển bản thân
                    </button>
                </div>

                <div id="bestSellersBooks" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Books will be loaded here by JavaScript -->
                </div>
            </div>
        </section>

        <!-- New Releases -->
        <section class="py-16 bg-cream-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-warm-gray-900 mb-4">Sách mới phát hành</h2>
                    <p class="text-warm-gray-600">Những cuốn sách mới nhất vừa ra mắt</p>
                </div>

                <div id="newReleasesBooks" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Books will be loaded here by JavaScript -->
                </div>

                <div class="text-center mt-12">
                    <a href="books.html" class="bg-terracotta-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-terracotta-700 transition-colors">
                        Xem tất cả sách mới
                    </a>
                </div>
            </div>
        </section>
@endsection
