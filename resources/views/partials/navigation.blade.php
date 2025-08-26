<link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
<nav class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-4">
    <ul class="flex space-x-8 text-gray-700 font-medium">
      <!-- Trang chủ -->
      <li class="py-4">
        <a href="#" class="hover:text-red-500">Trang chủ</a>
      </li>

      <!-- Danh mục sách -->
      <li class="relative group py-4">
        <button class="flex items-center gap-1 hover:text-red-500">
          Danh mục sách
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <!-- Dropdown -->
       <ul class="absolute left-0 mt-4 w-56 bg-white shadow-lg border border-gray-100 rounded-lg hidden group-hover:block z-50">
            <li class="relative group px-0 py-0 business-book">
                <a href="#" class="flex items-center justify-between w-full px-4 py-2 hover:text-red-500">
                    Sách Kinh Doanh
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <!-- Submenu cấp 2 -->
                <ul class="business-book-menu absolute left-full top-0 w-48 bg-white shadow-lg rounded-lg hidden  z-50">
                    <li class=" hover:bg-gray-100"><a class="block w-full px-4 py-2" href="#">Sách Huyền Học</a></li>
                    <li class=" hover:bg-gray-100"><a class="block w-full px-4 py-2" href="#">Combo Sách Hot</a></li>
                    <li class=" hover:bg-gray-100"><a class="block w-full px-4 py-2" href="#">Sách Tâm Lý Học</a></li>
                </ul>
            </li>

            <li class="hover:bg-gray-100"><a href="#" class="block w-full px-4 py-2">Sách Huyền Học</a></li>
            <li class="hover:bg-gray-100"><a href="#" class="block w-full px-4 py-2">Combo Sách Hot</a></li>
            <li class="hover:bg-gray-100"><a href="#" class="block w-full px-4 py-2">Sách Tâm Lý Học</a></li>
            <li class="hover:bg-gray-100"><a href="#" class="block w-full px-4 py-2">Sách Học Tập</a></li>
            <li class="hover:bg-gray-100"><a href="#" class="block w-full px-4 py-2">Sách Văn Học - Tiểu Thuyết</a></li>
            <li class="hover:bg-gray-100"><a href="#" class="block w-full px-4 py-2">Kiến Thức - Bách Khoa</a></li>
            <li class="hover:bg-gray-100"><a href="#" class="block w-full px-4 py-2">Sống Xanh - Dinh dưỡng - Sức khỏe</a></li>
            <li class="hover:bg-gray-100"><a href="#" class="block w-full px-4 py-2">Đầu tư - Tài chính</a></li>
            <li class="hover:bg-gray-100"><a href="#" class="block w-full px-4 py-2">Thiếu Nhi</a></li>
            <li class="hover:bg-gray-100"><a href="#" class="block w-full px-4 py-2">Tham khảo - Học tập</a></li>
        </ul>





      </li>

      <!-- Giới thiệu -->
      <li class="relative group py-4">
        <button class="flex items-center gap-1 hover:text-red-500">
          Giới thiệu
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <!-- Dropdown -->
        <ul class="absolute left-0 mt-2 w-48 bg-white shadow-lg border border-gray-100 rounded-lg hidden group-hover:block z-50">
          <li class="px-4 py-2 hover:bg-gray-100"><a href="#">Về chúng tôi</a></li>
          <li class="px-4 py-2 hover:bg-gray-100"><a href="#">Chính sách bảo mật</a></li>
          <li class="px-4 py-2 hover:bg-gray-100"><a href="#">Điều khoản dịch vụ</a></li>
        </ul>
      </li>

      <!-- Blog -->
      <li class="relative group py-4">
        <button class="flex items-center gap-1 hover:text-red-500">
          Blog
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <!-- Dropdown -->
        <ul class="absolute left-0 mt-2 w-48 bg-white shadow-lg border border-gray-100 rounded-lg hidden group-hover:block z-50">
          <li class="px-4 py-2 hover:bg-gray-100"><a href="#">Tin tức</a></li>
          <li class="px-4 py-2 hover:bg-gray-100"><a href="#">Kinh nghiệm đọc sách</a></li>
          <li class="px-4 py-2 hover:bg-gray-100"><a href="#">Review sách</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

