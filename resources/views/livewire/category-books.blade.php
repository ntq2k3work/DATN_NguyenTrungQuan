<div>
    <div class="flex items-center justify-between mb-4 p-3 bg-gray-50 rounded-lg">
        <div>
            <p class="text-sm text-gray-600">
                Hiển thị {{ $books->count() }} trong tổng số {{ $books->total() }} sách
            </p>
        </div>

        <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Sắp xếp:</label>
            <select wire:model.live="sortBy"
                    wire:loading.attr="disabled"
                    class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:ring-purple-500 focus:border-purple-500 disabled:opacity-50">
                <option value="default">Mặc định</option>
                <option value="price_asc">Giá tăng dần</option>
                <option value="price_desc">Giá giảm dần</option>
                <option value="name_asc">Tên A-Z</option>
                <option value="name_desc">Tên Z-A</option>
                <option value="newest">Mới nhất</option>
                <option value="oldest">Cũ nhất</option>
            </select>
            <div wire:loading wire:target="sortBy" class="text-sm text-gray-500">
                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
    </div>

    @if($books->count() > 0)
        <div wire:loading.class="opacity-50" wire:target="sortBy,updateFilters" class="transition-opacity duration-200">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($books as $index => $book)
                    @if($categoryType === 'top-selling')
                        @livewire('book-card', ['book' => $book, 'cardStyle' => 'top-selling', 'rank' => $index + 1], key('category-book-' . $book->id . '-' . $index))
                    @elseif($categoryType === 'best-sellers')
                        @livewire('book-card', ['book' => $book, 'cardStyle' => 'best-seller'], key('category-book-' . $book->id . '-' . $index))
                    @elseif($categoryType === 'new-releases')
                        @livewire('book-card', ['book' => $book, 'cardStyle' => 'new-release'], key('category-book-' . $book->id . '-' . $index))
                    @else
                        @livewire('book-card', ['book' => $book, 'cardStyle' => 'default'], key('category-book-' . $book->id . '-' . $index))
                    @endif
                @endforeach
            </div>

            <div class="mt-8 sm:mt-12 flex justify-center">
                {{ $books->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Không tìm thấy sách</h3>
            <p class="text-gray-500 mb-4">Không có sách nào phù hợp với bộ lọc hiện tại.</p>
            <button wire:click="clearFilters" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Xóa bộ lọc
            </button>
        </div>
    @endif
</div>
