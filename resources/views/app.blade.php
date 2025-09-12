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
        <div class="group bg-amber-50 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 cursor-pointer" onclick="window.location.href='{{ route('product.show', $book->slug) }}'">
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
                onclick="event.stopPropagation(); toggleWishlist({{ $book->id }})"
                data-book-id="{{ $book->id }}"
                title="Thêm vào yêu thích"
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
                <p class="text-sm text-gray-500">{{ $book->author?->name ?? 'Unknown' }}</p>
              </div>

              <div class="flex items-center gap-2">
                <div class="flex items-center text-blue-600">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                  <span class="text-sm text-gray-500">Số lượng: {{ $book->quantity ?? 0 }}</span>
                </div>
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
                class="w-full bg-amber-600 text-white text-sm py-2 px-4 rounded hover:bg-amber-500 cursor-pointer transition add-to-cart-btn"
                data-book-id="{{ $book->id }}"
                onclick="event.stopPropagation(); addToCart({{ $book->id }})"
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
          <div class="group hover:shadow-lg transition-shadow duration-300 bg-amber-50 rounded-lg overflow-hidden cursor-pointer" onclick="window.location.href='{{ route('product.show', $book->slug) }}'">
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
                  <p class="text-sm text-muted-foreground">{{ $book->author?->name ?? 'Unknown' }}</p>
                </div>
                <div class="flex items-center gap-2">
                  <div class="flex items-center text-blue-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="text-sm text-muted-foreground">Số lượng: {{ $book->quantity ?? 0 }}</span>
                  </div>
                </div>
                <div class="flex items-center justify-between">
                  <div class="flex flex-col">
                    <span class="text-lg font-bold text-orange-500">{{ number_format($book->final_price,0,',','.') }}đ</span>
                    <span class="text-sm text-muted-foreground line-through text-teal-600">{{ number_format($book->price,0,',','.') }}</span>
                  </div>
                  <div class="flex gap-2">
                    <button 
                      class="border border-gray-300 p-2 rounded hover:bg-gray-100" 
                      onclick="event.stopPropagation(); toggleWishlist({{ $book->id }})"
                      data-book-id="{{ $book->id }}"
                      title="Thêm vào yêu thích"
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
                    <button 
                      class="bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-500 cursor-pointer add-to-cart-btn"
                      data-book-id="{{ $book->id }}"
                      onclick="event.stopPropagation(); addToCart({{ $book->id }})"
                    >
                      Thêm vào giỏ
                    </button>
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
        <a href="{{ route('categories.best-sellers') }}" class="border border-gray-300 text-gray-700 py-3 px-6 rounded hover:bg-gray-100">
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
        <div class="group hover:shadow-lg transition-shadow duration-300 bg-amber-50 rounded-lg overflow-hidden cursor-pointer" onclick="window.location.href='{{ route('product.show', $book->slug) }}'">
          <div class="p-4">
            <div class="relative mb-4">
              <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}"
                class="w-full h-[280px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300" />
              <div class="absolute top-2 left-2 flex flex-col gap-2">
                <span class="bg-secondary text-secondary-foreground px-2 py-1 text-xs rounded">Kinh doanh</span>
              </div>
              <button
                class="absolute top-2 right-2 bg-background/80 hover:bg-background p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                onclick="event.stopPropagation(); toggleWishlist({{ $book->id }})"
                data-book-id="{{ $book->id }}"
                title="Thêm vào yêu thích"
              >
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
                <p class="text-sm text-muted-foreground">{{ $book->author?->name ?? 'Unknown' }}</p>
              </div>

              <div class="flex items-center gap-2">
                <div class="flex items-center text-blue-600">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                  <span class="text-sm text-muted-foreground">Số lượng: {{ $book->quantity ?? 0 }}</span>
                </div>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="text-lg font-bold text-primary text-orange-500">{{ $book->final_price }}đ</span>
                  <span class="text-sm text-muted-foreground line-through text-teal-600">{{ $book->price }}đ</span>
                </div>
              </div>

              <button 
                class="w-full px-4 py-2 rounded bg-amber-600 text-white hover:bg-amber-500 cursor-pointer text-sm add-to-cart-btn"
                data-book-id="{{ $book->id }}"
                onclick="event.stopPropagation(); addToCart({{ $book->id }})"
              >
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

  <!-- Toast Notification -->
  <div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50">
    <div class="flex items-center">
      <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
      </svg>
      <span id="toast-message">Đã thêm vào giỏ hàng!</span>
    </div>
  </div>

  <script>
    function addToCart(bookId, button = null) {
      console.log('addToCart called with bookId:', bookId);
      
      // If button is not provided, find the first button with the book ID
      if (!button) {
        button = document.querySelector(`[data-book-id="${bookId}"]`);
      }
      
      if (!button) {
        console.error('Button not found for book ID:', bookId);
        return;
      }
      
      console.log('Button found:', button);
      const originalText = button.textContent;
      
      // Disable button and show loading
      button.disabled = true;
      button.textContent = 'Đang thêm...';
      button.classList.add('opacity-50');
      
      // Make API call
      fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          book_id: bookId,
          quantity: 1
        })
      })
      .then(response => {
        console.log('Response status:', response.status);
        return response.json();
      })
      .then(data => {
        console.log('Response data:', data);
        if (data.success) {
          // Show success message
          showToast(data.message);
          
          // Update cart count if cart icon exists
          updateCartCount(data.cart_count);
          
          // Change button text temporarily
          button.textContent = 'Đã thêm!';
          button.classList.remove('bg-amber-600', 'hover:bg-amber-500');
          button.classList.add('bg-green-600');
          
          setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-amber-600', 'hover:bg-amber-500');
          }, 2000);
        } else {
          showToast(data.error || 'Có lỗi xảy ra', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
      })
      .finally(() => {
        // Re-enable button
        button.disabled = false;
        button.classList.remove('opacity-50');
      });
    }
    
    function showToast(message, type = 'success') {
      const toast = document.getElementById('toast');
      const toastMessage = document.getElementById('toast-message');
      
      toastMessage.textContent = message;
      
      // Change color based on type
      if (type === 'error') {
        toast.classList.remove('bg-green-500');
        toast.classList.add('bg-red-500');
      } else {
        toast.classList.remove('bg-red-500');
        toast.classList.add('bg-green-500');
      }
      
      // Show toast
      toast.classList.remove('translate-x-full');
      
      // Hide toast after 3 seconds
      setTimeout(() => {
        toast.classList.add('translate-x-full');
      }, 3000);
    }
    
    function updateCartCount(count) {
      // Update cart count in header if exists
      const cartCountElement = document.querySelector('.cart-count');
      if (cartCountElement) {
        cartCountElement.textContent = count;
      }
    }

    // Wishlist function for home page
    function toggleWishlist(bookId) {
      if (window.WishlistManager) {
        window.WishlistManager.toggleWishlist(bookId);
      } else {
        // Fallback if WishlistManager is not loaded
        console.error('WishlistManager not found');
      }
    }
  </script>
  
  <!-- Include wishlist functionality -->
  <script src="{{ asset('js/wishlist.js') }}"></script>
@endsection
