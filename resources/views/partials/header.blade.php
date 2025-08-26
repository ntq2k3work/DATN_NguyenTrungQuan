<div class="bg-white pb-3">
  <!-- Top bar -->
  <div class="bg-pink-50 p-2">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 px-4">
      
      <!-- Logo -->
      <a href="{{ route('home') }}" class="flex-shrink-0">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12">
      </a>

      <!-- Search (ẩn trên mobile) -->
      <div class="hidden md:flex flex-1 justify-center">
        <div class="flex w-full max-w-2xl">
          <input type="text" placeholder="Tìm kiếm sản phẩm..." class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none focus:border-red-500">
          <button class="bg-red-500 text-white px-4 rounded-r-lg hover:bg-red-600 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Right section -->
      <div class="hidden md:flex items-center gap-6">
        <!-- Giao hàng -->
        <div class="text-sm">
          <span class="text-gray-500">Giao hàng</span>
          <div class="font-medium text-gray-700">Nhà 20H2, ngõ 6 ...</div>
        </div>

        <!-- Đăng nhập -->
        <div class="relative">
            <!-- Nút Đăng nhập / Đăng ký -->
            <div id="loginBtn" class="flex items-center gap-1 text-sm text-gray-700 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A7 7 0 1117.804 5.121a7 7 0 01-12.683 12.683z" />
                </svg>
                <div>
                <div class="hover:text-red-500">Đăng nhập / Đăng ký</div>
                <div>Tài khoản của tôi</div>
                </div>
            </div>

            <!-- Dropdown Form -->
            <div id="loginDropdown"
                class="hidden absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg p-4 z-50">
                <h2 class="text-center text-red-600 font-bold text-lg mb-2">ĐĂNG NHẬP TÀI KHOẢN</h2>
                <p class="text-center text-sm text-gray-600 mb-4">Nhập email và mật khẩu của bạn:</p>

                <form>
                <input type="email" placeholder="Email"
                    class="w-full border border-gray-300 rounded-md p-2 mb-2 focus:outline-none focus:ring-2 focus:ring-red-400">
                <input type="password" placeholder="Mật khẩu"
                    class="w-full border border-gray-300 rounded-md p-2 mb-2 focus:outline-none focus:ring-2 focus:ring-red-400">
                <p class="text-xs text-gray-500 mb-2">
                    This site is protected by reCAPTCHA and the Google
                    <a href="#" class="text-blue-600">Privacy Policy</a> and
                    <a href="#" class="text-blue-600">Terms of Service</a> apply.
                </p>
                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-md hover:bg-red-700">ĐĂNG NHẬP</button>
                </form>

                <div class="flex justify-between mt-3 text-sm">
                <a href="{{ route('register') }}" class="text-blue-600">Tạo tài khoản</a>
                <a href="#" class="text-blue-600">Khôi phục mật khẩu</a>
                </div>
            </div>
        </div>


        <!-- Giỏ hàng -->
        <div class="relative flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9m-6-9v9" />
          </svg>
          <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1">0</span>
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
    <input type="text" placeholder="Tìm kiếm sản phẩm..." class="w-full mb-4 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-red-500">
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

  loginBtn.addEventListener('click', () => {
    loginDropdown.classList.toggle('hidden');
  });

  // Click ngoài để đóng dropdown
  document.addEventListener('click', (e) => {
    if (!loginBtn.contains(e.target) && !loginDropdown.contains(e.target)) {
      loginDropdown.classList.add('hidden');
    }
  });
</script>
