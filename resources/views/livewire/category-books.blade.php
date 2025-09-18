<div>
    <div class="flex items-center justify-between mb-4 p-3 bg-gray-50 rounded-lg">
        <div>
            <p class="text-sm text-gray-600">
                Hiển thị {{ $books->count() }} trong tổng số {{ $books->total() }} sách
            </p>
        </div>

        <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Sắp xếp:</label>
            <select wire:model.live="sortBy" class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:ring-purple-500 focus:border-purple-500">
                <option value="default">Mặc định</option>
                <option value="price_asc">Giá tăng dần</option>
                <option value="price_desc">Giá giảm dần</option>
                <option value="name_asc">Tên A-Z</option>
                <option value="name_desc">Tên Z-A</option>
                <option value="newest">Mới nhất</option>
                <option value="oldest">Cũ nhất</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        @foreach ($books as $index => $book)
            @if($categoryType === 'top-selling')
                @livewire('book-card', ['book' => $book, 'cardStyle' => 'top-selling', 'rank' => $index + 1], key('category-book-' . $book->id))
            @elseif($categoryType === 'best-sellers')
                @livewire('book-card', ['book' => $book, 'cardStyle' => 'best-seller'], key('category-book-' . $book->id))
            @elseif($categoryType === 'new-releases')
                @livewire('book-card', ['book' => $book, 'cardStyle' => 'new-release'], key('category-book-' . $book->id))
            @else
                @livewire('book-card', ['book' => $book, 'cardStyle' => 'default'], key('category-book-' . $book->id))
            @endif
        @endforeach
    </div>

    @if($books->count() > 0)
        <div class="mt-8 sm:mt-12 flex justify-center">
            {{ $books->links() }}
        </div>
    @endif
</div>
