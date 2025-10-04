<footer class="bg-foreground text-white bg-gray-800">
    <div class="container mx-auto px-4">

      <!-- Newsletter section -->
      <div class="py-12 border-b border-background/20">
        <div class="max-w-2xl mx-auto text-center">
          <h3 class="text-2xl font-bold mb-4">Đăng ký nhận thông tin sách mới</h3>
          <p class="text-background/80 mb-6">
            Nhận thông báo về những cuốn sách mới, ưu đãi đặc biệt và các sự kiện thú vị
          </p>
          <div class="flex gap-4 max-w-md mx-auto">
            <form id="newsletter-form" class="flex gap-4 w-full">
              @csrf
              <input type="email" name="email" id="newsletter-email" placeholder="Nhập email của bạn" required
                class="bg-background text-foreground w-full px-4 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary" />
              <button type="submit" id="newsletter-submit"
                class="bg-amber-600 hover:bg-amber-700 cursor-pointer inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 aria-invalid:border-destructive bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 py-2">
                <span id="newsletter-text">Đăng ký</span>
                <span id="newsletter-loading" class="hidden">
                  <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </span>
              </button>
            </form>
          </div>
          <div id="newsletter-message" class="mt-4 text-sm hidden"></div>
        </div>
      </div>

      <!-- Main footer content -->
      <div class="py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

          <!-- Company info -->
          <div class="space-y-4">
            <h4 class="text-xl font-bold text-primary">BookStore</h4>
            <p class="text-background/80 text-sm leading-relaxed">
              Cửa hàng sách trực tuyến hàng đầu Việt Nam, mang đến cho bạn những cuốn sách chất lượng với giá tốt nhất.
            </p>
          </div>

          <!-- Quick links -->
          {{-- <div class="space-y-4">
            <h4 class="font-semibold text-lg">Liên kết nhanh</h4>
            <ul class="space-y-2 text-sm">
              <li><a href="#" class="text-background/80 hover:text-background transition-colors">Về chúng tôi</a></li>
              <li><a href="#" class="text-background/80 hover:text-background transition-colors">Liên hệ</a></li>
              <li><a href="#" class="text-background/80 hover:text-background transition-colors">Tuyển dụng</a></li>
              <li><a href="#" class="text-background/80 hover:text-background transition-colors">Tin tức</a></li>
              <li><a href="#" class="text-background/80 hover:text-background transition-colors">Sự kiện</a></li>
            </ul>
          </div> --}}

          <!-- Customer service -->
          <div class="space-y-4">
            <h4 class="font-semibold text-lg">Hỗ trợ khách hàng</h4>
            <ul class="space-y-2 text-sm">
              <li><a href="{{ route('help.shopping-guide') }}" class="text-background/80 hover:text-background transition-colors">Hướng dẫn mua hàng</a></li>
              <li><a href="{{ route('help.return-policy') }}" class="text-background/80 hover:text-background transition-colors">Chính sách đổi trả</a></li>
              <li><a href="{{ route('help.payment-methods') }}" class="text-background/80 hover:text-background transition-colors">Phương thức thanh toán</a></li>
              <li><a href="{{ route('help.shipping-info') }}" class="text-background/80 hover:text-background transition-colors">Vận chuyển</a></li>
              <li><a href="{{ route('help.faq') }}" class="text-background/80 hover:text-background transition-colors">FAQ</a></li>
            </ul>
          </div>

          <!-- Contact info -->
          <div class="space-y-4">
            <h4 class="font-semibold text-lg">Thông tin liên hệ</h4>
            <div class="space-y-3 text-sm">
              <div class="flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-background/80">Ngõ 82 Mỹ Đình, Hà Nội</span>
              </div>
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <a href="tel:19001234">
                    <button>1900 1234</button>
                </a>
              </div>
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="text-background/80">support@bookstore.vn</span>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Bottom bar -->
      <div class="py-6 border-t border-background/20">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
          <p class="text-background/60 text-sm">© 2025 BookStore. Tất cả quyền được bảo lưu.</p>
          <div class="flex gap-6 text-sm">
            <a href="#" class="text-background/60 hover:text-background transition-colors">Điều khoản sử dụng</a>
            <a href="#" class="text-background/60 hover:text-background transition-colors">Chính sách bảo mật</a>
            <a href="#" class="text-background/60 hover:text-background transition-colors">Cookie</a>
          </div>
        </div>
      </div>

    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const newsletterForm = document.getElementById('newsletter-form');
      const newsletterEmail = document.getElementById('newsletter-email');
      const newsletterSubmit = document.getElementById('newsletter-submit');
      const newsletterText = document.getElementById('newsletter-text');
      const newsletterLoading = document.getElementById('newsletter-loading');
      const newsletterMessage = document.getElementById('newsletter-message');

      if (newsletterForm) {
        newsletterForm.addEventListener('submit', async function(e) {
          e.preventDefault();

          const email = newsletterEmail.value.trim();

          if (!email) {
            showMessage('Vui lòng nhập địa chỉ email.', 'error');
            return;
          }

          if (!isValidEmail(email)) {
            showMessage('Địa chỉ email không hợp lệ.', 'error');
            return;
          }

          // Show loading state
          setLoading(true);

          try {
            const response = await fetch('{{ route("newsletter.subscribe") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                               document.querySelector('input[name="_token"]')?.value
              },
              body: JSON.stringify({ email: email })
            });

            const data = await response.json();

            if (data.success) {
              showMessage(data.message, 'success');
              newsletterEmail.value = '';
            } else {
              showMessage(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.', 'error');
            }
          } catch (error) {
            console.error('Newsletter subscription error:', error);
            showMessage('Có lỗi xảy ra. Vui lòng thử lại.', 'error');
          } finally {
            setLoading(false);
          }
        });
      }

      function setLoading(loading) {
        newsletterSubmit.disabled = loading;
        if (loading) {
          newsletterText.classList.add('hidden');
          newsletterLoading.classList.remove('hidden');
        } else {
          newsletterText.classList.remove('hidden');
          newsletterLoading.classList.add('hidden');
        }
      }

      function showMessage(message, type) {
        newsletterMessage.textContent = message;
        newsletterMessage.className = `mt-4 text-sm ${type === 'success' ? 'text-green-400' : 'text-red-400'}`;
        newsletterMessage.classList.remove('hidden');

        // Hide message after 5 seconds
        setTimeout(() => {
          newsletterMessage.classList.add('hidden');
        }, 5000);
      }

      function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
      }
    });
  </script>
