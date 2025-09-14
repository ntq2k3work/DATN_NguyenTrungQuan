<link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
<style>
  /* Ensure header stays at top */
  .bg-white.pb-3 {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    z-index: 1000 !important;
    background: white !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
  }
  
  /* User dropdown hover improvements */
  .user-dropdown-container {
    position: relative;
  }

  .user-dropdown-container::before {
    content: '';
    position: absolute;
    top: -10px;
    left: -10px;
    right: -10px;
    bottom: -10px;
    z-index: -1;
  }

  .user-dropdown {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateY(-10px);
    opacity: 0;
    visibility: hidden;
  }

  .user-dropdown-container:hover .user-dropdown {
    opacity: 1 !important;
    visibility: visible !important;
    transform: translateY(0) !important;
  }

  /* Ensure smooth transitions */
  .group:hover .user-dropdown {
    opacity: 1 !important;
    visibility: visible !important;
    transform: translateY(0) !important;
  }

  /* Hover bridge to prevent gap */
  .user-dropdown-container:hover::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    height: 20px;
    background: transparent;
  }

  /* Smooth icon rotation */
  .group:hover svg.transition-transform {
    transform: rotate(180deg);
  }
</style>

<div class="bg-white pb-3">
  <!-- Top bar -->
  <div class="bg-pink-50 p-2">
    <div class="container mx-auto flex items-center justify-between gap-4 px-4">

      <!-- Logo -->
      <a href="{{ route('home') }}" class="flex-shrink-0">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12">
      </a>

      <!-- Search (ẩn trên mobile) -->
      <div class="hidden md:flex flex-1 justify-center">
        <div class="relative flex w-full max-w-2xl">
          <div class="relative w-full">
            <input 
              type="text" 
              id="search-input"
              placeholder="Tìm kiếm sản phẩm..." 
              class="w-full border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none focus:border-red-500"
              autocomplete="off"
            >
            <!-- Loading spinner -->
            <div id="search-loading" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
              <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </div>
            
            <!-- Search suggestions dropdown -->
            <div id="search-suggestions" class="absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-50 hidden max-h-80 overflow-y-auto">
              <div id="suggestions-list" class="py-2">
                <!-- Suggestions will be populated here -->
              </div>
            </div>
            
            <!-- Search results dropdown -->
            <div id="search-results" class="absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-50 hidden max-h-96 overflow-y-auto">
              <div id="results-list" class="py-2">
                <!-- Search results will be populated here -->
              </div>
            </div>
          </div>
          <button 
            id="search-btn"
            class="bg-red-500 text-white px-4 rounded-r-lg hover:bg-red-600 flex items-center justify-center transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Right section -->
      <div class="hidden md:flex items-center gap-6">
        @auth
          <!-- User is logged in - User Account Section -->
          <div class="relative group user-dropdown-container">
            <!-- User Account Button -->
            <div class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer hover:text-red-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <div>
                <div class="font-medium">{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-500">Tài khoản của tôi</div>
              </div>
              <svg class="h-4 w-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </div>

            <!-- User Dropdown Menu -->
            <div class="user-dropdown absolute right-0 mt-0 w-64 bg-white shadow-lg rounded-lg border border-gray-100 opacity-0 invisible transform translate-y-2 z-50">
              <!-- Invisible bridge to prevent gap -->
              <div class="absolute -top-2 left-0 right-0 h-2 bg-transparent"></div>

              <div class="p-4 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                  <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                  </div>
                  <div>
                    <div class="font-medium text-gray-900">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    @if(Auth::user()->hasVerifiedEmail())
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Đã xác thực
                      </span>
                    @else
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Chưa xác thực
                      </span>
                    @endif
                  </div>
                </div>
              </div>

              <div class="p-2">
                <a href="{{ route('profile') }}" class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 transition-colors">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  <span>Hồ sơ cá nhân</span>
                </a>

                <a href="{{ route('orders.index') }}" class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 transition-colors">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                  </svg>
                  <span>Đơn hàng của tôi</span>
                </a>

                <a href="{{ route('wishlist.index') }}" class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 transition-colors">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                  </svg>
                  <span>Sản phẩm yêu thích</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 transition-colors">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  <span>Cài đặt</span>
                </a>
              </div>

              <div class="p-2 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}" class="block">
                  @csrf
                  <button type="submit" class="w-full flex items-center space-x-3 px-3 py-2 text-sm text-red-600 rounded-md hover:bg-red-50 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Đăng xuất</span>
                  </button>
                </form>
              </div>
            </div>
          </div>
        @else
          <!-- User is not logged in - Login/Register Section -->
          <div class="relative">
            <!-- Nút Đăng nhập / Đăng ký -->
            <div id="loginBtn" class="flex items-center gap-1 text-sm text-gray-700 cursor-pointer hover:text-red-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7 7 0 1117.804 5.121a7 7 0 01-12.683 12.683z" />
              </svg>
              <div>
                <div>Đăng nhập / Đăng ký</div>
                <div class="text-xs text-gray-500">Tài khoản của tôi</div>
              </div>
            </div>

            <!-- Login/Register Links -->
            <div id="loginDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg border border-gray-100 z-50">
              <div class="p-4">
                <div class="space-y-3">
                  <a href="{{ route('login') }}" class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Đăng nhập</span>
                  </a>

                  <a href="{{ route('register') }}" class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <span>Đăng ký</span>
                  </a>

                  <a href="{{ route('password.request') }}" class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7 7m0 0a6 6 0 01-7-7m7 7v4m0 0H9m3 0h3m-3-4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span>Quên mật khẩu</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endauth

        <!-- Wishlist -->
        <div class="relative flex items-center cursor-pointer" onclick="window.location.href='{{ route('wishlist.index') }}'">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
          <span class="wishlist-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1">{{ $wishlistCount ?? 0 }}</span>
          <span class="ml-2 text-sm">Yêu thích</span>
        </div>

        <!-- Giỏ hàng -->
        <div class="relative flex items-center cursor-pointer" onclick="window.location.href='{{ route('cart.show') }}'">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9m-6-9v9" />
          </svg>
          <span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1">{{ $cartCount ?? 0 }}</span>
          <span class="ml-2 text-sm">Giỏ hàng</span>
        </div>
      </div>

      <!-- Nút Menu Mobile -->
      <button id="mobile-menu-btn" class="md:hidden flex items-center text-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
        </svg>
      </button>
    </div>
  </div>

  <!-- Navigation -->
  <div class="hidden md:block bg-white shadow" id="desktop-nav">
    @include('partials.navigation')
  </div>

  <!-- Mobile menu -->
  <div id="mobile-menu" class="hidden bg-white shadow p-4 md:hidden">
    <div class="relative mb-4">
      <input 
        type="text" 
        id="mobile-search-input"
        placeholder="Tìm kiếm sản phẩm..." 
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-red-500"
        autocomplete="off"
      >
      <!-- Mobile loading spinner -->
      <div id="mobile-search-loading" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
        <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
      
      <!-- Mobile search suggestions dropdown -->
      <div id="mobile-search-suggestions" class="absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-50 hidden max-h-80 overflow-y-auto">
        <div id="mobile-suggestions-list" class="py-2">
          <!-- Mobile suggestions will be populated here -->
        </div>
      </div>
      
      <!-- Mobile search results dropdown -->
      <div id="mobile-search-results" class="absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-50 hidden max-h-96 overflow-y-auto">
        <div id="mobile-results-list" class="py-2">
          <!-- Mobile search results will be populated here -->
        </div>
      </div>
    </div>
    @include('partials.navigation')
  </div>
</div>

<script>
  const menuBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');

  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });

  const loginBtn = document.getElementById('loginBtn');
  const loginDropdown = document.getElementById('loginDropdown');

  if (loginBtn && loginDropdown) {
    loginBtn.addEventListener('click', () => {
      loginDropdown.classList.toggle('hidden');
    });

    // Click ngoài để đóng dropdown
    document.addEventListener('click', (e) => {
      if (!loginBtn.contains(e.target) && !loginDropdown.contains(e.target)) {
        loginDropdown.classList.add('hidden');
      }
    });
  }

  // Simple hover enhancement for user dropdown
  document.addEventListener('DOMContentLoaded', function() {
    const userContainer = document.querySelector('.user-dropdown-container');
    if (userContainer) {
      const dropdown = userContainer.querySelector('.user-dropdown');

      // Add a small delay when leaving to make it easier to navigate
      let leaveTimeout;

      userContainer.addEventListener('mouseenter', function() {
        clearTimeout(leaveTimeout);
        if (dropdown) {
          dropdown.style.opacity = '1';
          dropdown.style.visibility = 'visible';
          dropdown.style.transform = 'translateY(0)';
        }
      });

      userContainer.addEventListener('mouseleave', function() {
        leaveTimeout = setTimeout(() => {
          if (dropdown) {
            dropdown.style.opacity = '0';
            dropdown.style.visibility = 'hidden';
            dropdown.style.transform = 'translateY(-10px)';
          }
        }, 150); // Small delay to allow moving to dropdown
      });
    }
  });
</script>
