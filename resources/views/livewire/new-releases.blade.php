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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($newPublishers as $book)
            <div class="group hover:shadow-lg transition-shadow duration-300 bg-amber-50 rounded-lg overflow-hidden cursor-pointer" 
                 onclick="window.location.href='{{ route('product.show', $book->slug) }}'">
                <div class="p-4">
                    <div class="relative mb-4">
                        <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}"
                            class="w-full h-[280px] object-cover rounded-md group-hover:scale-105 transition-transform duration-300" />
                        <div class="absolute top-2 left-2 flex flex-col gap-2">
                            <span class="bg-secondary text-secondary-foreground px-2 py-1 text-xs rounded">Kinh doanh</span>
                        </div>
                        <button
                            class="absolute top-2 right-2 bg-background/80 hover:bg-background p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                            onclick="event.stopPropagation();"
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

                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Phát hành: {{ date_format($book->created_at,'d/m/Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-bold text-primary text-orange-500">{{ number_format($book->final_price,0,',','.') }}đ</span>
                                <span class="text-sm text-muted-foreground line-through text-teal-600">{{ number_format($book->price,0,',','.') }}đ</span>
                            </div>
                        </div>

                        <div onclick="event.stopPropagation();">
                            <button 
                                wire:click="addToCart({{ $book->id }})"
                                class="w-full bg-primary text-white px-4 py-2 rounded hover:bg-primary/90 transition-colors"
                            >
                                <i class="fas fa-shopping-cart mr-2"></i>Thêm vào giỏ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Nút xem tất cả -->
        <div class="text-center mt-12">
            <a href="{{ route('categories.new-releases') }}"
                class="px-6 py-3 cursor-pointer rounded border border-input bg-white hover:bg-sky-50 hover:bg-accent hover:text-accent-foreground text-sm">
                Xem tất cả sách mới
            </a>
        </div>
    </div>

    <!-- Toast Notifications -->
    @if($showSuccessToast)
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)"
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50 max-w-sm w-full bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">{{ $toastMessage }}</p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button wire:click="hideToast" class="inline-flex text-white hover:text-gray-200">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($showErrorToast)
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)"
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50 max-w-sm w-full bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">{{ $toastMessage }}</p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button wire:click="hideToast" class="inline-flex text-white hover:text-gray-200">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif
</section>