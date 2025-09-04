<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - BookStore Admin</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .sidebar.collapsed {
            width: 4rem;
        }
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
        .main-content {
            transition: all 0.3s ease;
        }
        .sidebar.collapsed + .main-content {
            margin-left: 4rem;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed top-0 left-0 h-full w-64 bg-gray-800 text-white transition-all duration-300 z-50">
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <i class="fas fa-book text-2xl text-blue-400"></i>
                <span class="sidebar-text text-xl font-bold">BookStore Admin</span>
            </div>
            <button id="sidebarToggle" class="sidebar-text text-gray-400 hover:text-white">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <nav class="mt-6">
            <div class="px-4 mb-4">
                <span class="sidebar-text text-xs text-gray-400 uppercase tracking-wider">Quản lý</span>
            </div>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : '' }}">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span class="sidebar-text ml-3">Dashboard</span>
            </a>
            
            <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                <i class="fas fa-users w-5"></i>
                <span class="sidebar-text ml-3">Người dùng</span>
            </a>
            
            <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                <i class="fas fa-book w-5"></i>
                <span class="sidebar-text ml-3">Sách</span>
            </a>
            
            <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                <i class="fas fa-shopping-cart w-5"></i>
                <span class="sidebar-text ml-3">Đơn hàng</span>
            </a>
            
            <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                <i class="fas fa-tags w-5"></i>
                <span class="sidebar-text ml-3">Danh mục</span>
            </a>
            
            <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                <i class="fas fa-percent w-5"></i>
                <span class="sidebar-text ml-3">Khuyến mãi</span>
            </a>
            
            <div class="px-4 mt-6 mb-4">
                <span class="sidebar-text text-xs text-gray-400 uppercase tracking-wider">Hệ thống</span>
            </div>
            
            <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                <i class="fas fa-cog w-5"></i>
                <span class="sidebar-text ml-3">Cài đặt</span>
            </a>
            
            <a href="{{ route('admin.logout') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span class="sidebar-text ml-3">Đăng xuất</span>
            </a>
            
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content ml-64 min-h-screen">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-user-circle text-gray-600"></i>
                        <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="p-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Sidebar toggle functionality
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>
