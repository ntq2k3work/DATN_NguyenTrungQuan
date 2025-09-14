<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('script')
    @vite('resources/css/app.css')
    @yield('link-css')
    <title>@yield('title', 'BookStore ')</title>
</head>
<body>
    <header>
        @include('partials.header')
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        @include('partials.footer')
    </footer>

    <!-- Chatbot Button -->
    <div class="chatbot-container">
        <button 
            id="chatbot-toggle" 
            class="chatbot-button"
            aria-label="Mở chatbot hỗ trợ"
            tabindex="0"
        >
            <i class="fas fa-comments"></i>
        </button>
        
        <!-- Chatbot Modal -->
        <div id="chatbot-modal" class="chatbot-modal">
            <div class="chatbot-modal-content">
                <div class="chatbot-header">
                    <div class="chatbot-header-info">
                        <div class="chatbot-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="chatbot-title">
                            <h3>Hỗ trợ khách hàng</h3>
                            <p class="chatbot-status">Đang hoạt động</p>
                        </div>
                    </div>
                    <button 
                        id="chatbot-close" 
                        class="chatbot-close"
                        aria-label="Đóng chatbot"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="chatbot-body">
                    <div class="chatbot-messages" id="chatbot-messages">
                        <div class="chatbot-message chatbot-message-bot">
                            <div class="message-content">
                                <p>Xin chào! Tôi có thể giúp gì cho bạn hôm nay?</p>
                            </div>
                            <div class="message-time">Bây giờ</div>
                        </div>
                    </div>
                    
                    <div class="chatbot-input-container">
                        <div class="chatbot-input-wrapper">
                            <input 
                                type="text" 
                                id="chatbot-input" 
                                placeholder="Nhập tin nhắn của bạn..."
                                class="chatbot-input"
                            >
                            <button 
                                id="chatbot-send" 
                                class="chatbot-send"
                                aria-label="Gửi tin nhắn"
                            >
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/app.js')
    <script src="{{ asset('js/wishlist.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
    <script src="{{ asset('js/chatbot.js') }}"></script>
    @stack('scripts')
</body>
</html>
