<div>
    <!-- Pagination Controls -->
    <div class="flex items-center justify-between mb-6">
        <!-- Per Page Selector -->
        <div class="flex items-center space-x-2">
            <label for="per-page" class="text-sm text-gray-700">Hiển thị:</label>
            <select
                wire:model.live="perPage"
                id="per-page"
                class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
            >
                <option value="12">12</option>
                <option value="24">24</option>
                <option value="48">48</option>
            </select>
            <span class="text-sm text-gray-700">sản phẩm</span>
        </div>

        <!-- Sort Options -->
        <div class="flex items-center space-x-2">
            <label for="sort-by" class="text-sm text-gray-700">Sắp xếp:</label>
            <select
                wire:model.live="sortBy"
                id="sort-by"
                class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
            >
                <option value="created_at">Mới nhất</option>
                <option value="title">Tên A-Z</option>
                <option value="price">Giá thấp đến cao</option>
                <option value="final_price">Giá cao đến thấp</option>
                <option value="quantity">Số lượng</option>
            </select>

            @if($sortBy !== 'created_at')
                <button
                    wire:click="sortBy('{{ $sortBy }}')"
                    class="p-1 text-gray-500 hover:text-gray-700"
                    title="{{ $sortDirection === 'asc' ? 'Sắp xếp tăng dần' : 'Sắp xếp giảm dần' }}"
                >
                    @if($sortDirection === 'asc')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    @endif
                </button>
            @endif
        </div>
    </div>

    <!-- Pagination Links -->
    <div class="flex items-center justify-center space-x-2 mt-8">
        {{ $paginator->links() }}
    </div>

    <!-- Pagination Info -->
    <div class="text-center text-sm text-gray-600 mt-4">
        Hiển thị {{ $paginator->firstItem() ?? 0 }} đến {{ $paginator->lastItem() ?? 0 }}
        trong tổng số {{ $paginator->total() }} sản phẩm
    </div>
</div>
