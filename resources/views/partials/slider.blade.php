<section class="relative bg-amber-50 overflow-hidden">
<style>
  .slider-button {
    position: relative;
    z-index: 10;
    pointer-events: auto;
    cursor: pointer;
  }
</style>
    <div class="container mx-auto px-4">
      <div class="relative h-[500px] flex items-center">
        <div class="absolute inset-0 transition-opacity duration-500 opacity-100" data-slide="0">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-full items-center">
            <div class="space-y-6">
              <div class="space-y-4">
                <h1 class="text-4xl md:text-6xl font-bold text-warm-gray-900 mb-6">
                    Khám phá thế giới <br><span class="text-terracotta-600">tri thức</span>
                </h1>
                <h3 class="text-xl text-primary font-medium">Những cuốn sách hay được gợi ý riêng cho bạn</h3>
                <p class="text-muted-foreground text-lg leading-relaxed">
                  Từ văn học kinh điển đến sách kỹ năng hiện đại, tìm kiếm cuốn sách phù hợp với sở thích của bạn
                </p>
              </div>
              <div class="flex gap-4">
                <a href="{{ route('categories.index') }}" class="slider-button bg-amber-600 text-white px-8 py-3 rounded-lg hover:bg-primary/90 transition-colors">Khám phá ngay</a>
              </div>
            </div>
            <div>
              <img src="{{ asset('images/cozy-reading-corner-with-books-and-warm-lighting.png') }}" alt="slide 1"
                class="w-full h-[400px] object-cover rounded-lg shadow-lg" />
            </div>
          </div>
        </div>

        <div class="absolute inset-0 transition-opacity duration-500 opacity-0" data-slide="1">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-full items-center">
            <div class="space-y-6">
              <div class="space-y-4">
                <h1 class="text-4xl md:text-6xl font-bold text-warm-gray-900 mb-6">
                    Sách mới phát hành
                </h1>
                <h3 class="text-xl text-primary font-medium">Cập nhật những đầu sách hot nhất tháng này</h3>
                <p class="text-muted-foreground text-lg leading-relaxed">
                  Đừng bỏ lỡ những cuốn sách được mong đợi nhất từ các tác giả nổi tiếng
                </p>
              </div>
              <div class="flex gap-4">
                <a href="{{ route('categories.new-releases') }}" class="slider-button px-8 py-3 bg-amber-600 text-white rounded-lg hover:bg-primary/90 transition-colors">Xem sách mới</a>
              </div>
            </div>
            <div>
              <img src="{{ asset('images/stack-of-new-books-with-modern-covers.png') }}" alt="slide 2"
                class="w-full h-[400px] object-cover rounded-lg shadow-lg" />
            </div>
          </div>
        </div>

        <div class="absolute inset-0 transition-opacity duration-500 opacity-0" data-slide="2">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-full items-center">
            <div class="space-y-6">
              <div class="space-y-4">
                <h1 class="text-4xl md:text-6xl font-bold text-warm-gray-900 mb-6">
                    Ưu đãi đặc biệt
                </h1>
                <h3 class="text-xl text-primary font-medium">Giảm giá lên đến 50% cho sách best-seller</h3>
                <p class="text-muted-foreground text-lg leading-relaxed">
                  Cơ hội sở hữu những cuốn sách yêu thích với giá ưu đãi nhất
                </p>
              </div>
              <div class="flex gap-4">
                <a href="{{ route('categories.best-sellers') }}" class="slider-button bg-amber-600 text-white px-8 py-3 rounded-lg hover:bg-primary/90 transition-colors">Mua ngay</a>
              </div>
            </div>
            <div>
              <img src="{{ asset('images/bookstore-sale-with-discount-tags.png') }}" alt="slide 3"
                class="w-full h-[400px] object-cover rounded-lg shadow-lg" />
            </div>
          </div>
        </div>

        <button id="prevSlide"
          class="absolute bg-white -left-12 top-1/2 -translate-y-1/2 bg-background/80 hover:bg-background p-3 rounded-full shadow-lg z-20">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <button id="nextSlide"
          class="absolute bg-white -right-12 top-1/2 -translate-y-1/2 bg-background/80 hover:bg-background p-3 rounded-full shadow-lg z-20">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
          <button class="w-3 h-3 rounded-full bg-primary" data-indicator="0"></button>
          <button class="w-3 h-3 rounded-full bg-background/50" data-indicator="1"></button>
          <button class="w-3 h-3 rounded-full bg-background/50" data-indicator="2"></button>
        </div>
      </div>
    </div>
  </section>

  <script>
    const slides = document.querySelectorAll("[data-slide]");
    const indicators = document.querySelectorAll("[data-indicator]");
    let currentSlide = 0;
    let timer;

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.toggle("opacity-100", i === index);
        slide.classList.toggle("opacity-0", i !== index);
      });
      indicators.forEach((dot, i) => {
        dot.classList.toggle("bg-primary", i === index);
        dot.classList.toggle("bg-background/50", i !== index);
      });
      currentSlide = index;
    }

    function nextSlide() {
      showSlide((currentSlide + 1) % slides.length);
    }

    function prevSlide() {
      showSlide((currentSlide - 1 + slides.length) % slides.length);
    }

    document.getElementById("nextSlide").addEventListener("click", () => {
      nextSlide();
      resetTimer();
    });

    document.getElementById("prevSlide").addEventListener("click", () => {
      prevSlide();
      resetTimer();
    });

    indicators.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        showSlide(index);
        resetTimer();
      });
    });

    function startAutoSlide() {
      timer = setInterval(nextSlide, 5000);
    }

    function resetTimer() {
      clearInterval(timer);
      startAutoSlide();
    }

    startAutoSlide();
  </script>
