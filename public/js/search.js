/**
 * Search functionality with AJAX realtime search
 */
class SearchManager {
    constructor() {
        this.searchTimeout = null;
        this.suggestionsTimeout = null;
        this.isSearching = false;
        this.isSuggesting = false;
        
        this.initDesktopSearch();
        this.initMobileSearch();
        this.initClickOutside();
    }

    initDesktopSearch() {
        const searchInput = document.getElementById('search-input');
        const searchBtn = document.getElementById('search-btn');
        const searchLoading = document.getElementById('search-loading');
        const searchSuggestions = document.getElementById('search-suggestions');
        const searchResults = document.getElementById('search-results');
        const suggestionsList = document.getElementById('suggestions-list');
        const resultsList = document.getElementById('results-list');

        if (!searchInput) return;

        // Handle input events
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            
            if (query.length === 0) {
                this.hideDropdowns(searchSuggestions, searchResults);
                return;
            }

            if (query.length >= 2) {
                this.handleSuggestions(query, suggestionsList, searchSuggestions, searchLoading);
            }

            if (query.length >= 3) {
                this.handleSearch(query, resultsList, searchResults, searchLoading);
            }
        });

        // Handle search button click
        searchBtn.addEventListener('click', () => {
            const query = searchInput.value.trim();
            if (query.length >= 3) {
                this.performSearch(query, resultsList, searchResults, searchLoading);
            }
        });

        // Handle Enter key
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = searchInput.value.trim();
                if (query.length >= 3) {
                    this.performSearch(query, resultsList, searchResults, searchLoading);
                }
            }
        });

        // Handle focus
        searchInput.addEventListener('focus', () => {
            const query = searchInput.value.trim();
            if (query.length >= 2) {
                searchSuggestions.classList.remove('hidden');
            }
        });
    }

    initMobileSearch() {
        const searchInput = document.getElementById('mobile-search-input');
        const searchLoading = document.getElementById('mobile-search-loading');
        const searchSuggestions = document.getElementById('mobile-search-suggestions');
        const searchResults = document.getElementById('mobile-search-results');
        const suggestionsList = document.getElementById('mobile-suggestions-list');
        const resultsList = document.getElementById('mobile-results-list');

        if (!searchInput) return;

        // Handle input events
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            
            if (query.length === 0) {
                this.hideDropdowns(searchSuggestions, searchResults);
                return;
            }

            if (query.length >= 2) {
                this.handleSuggestions(query, suggestionsList, searchSuggestions, searchLoading);
            }

            if (query.length >= 3) {
                this.handleSearch(query, resultsList, searchResults, searchLoading);
            }
        });

        // Handle Enter key
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = searchInput.value.trim();
                if (query.length >= 3) {
                    this.performSearch(query, resultsList, searchResults, searchLoading);
                }
            }
        });

        // Handle focus
        searchInput.addEventListener('focus', () => {
            const query = searchInput.value.trim();
            if (query.length >= 2) {
                searchSuggestions.classList.remove('hidden');
            }
        });
    }

    handleSuggestions(query, suggestionsList, suggestionsDropdown, loadingElement) {
        clearTimeout(this.suggestionsTimeout);
        
        this.suggestionsTimeout = setTimeout(() => {
            this.isSuggesting = true;
            this.showLoading(loadingElement);
            
            fetch(`/search/suggestions?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    this.hideLoading(loadingElement);
                    this.isSuggesting = false;
                    
                    if (data.success && data.data.length > 0) {
                        this.renderSuggestions(data.data, suggestionsList);
                        suggestionsDropdown.classList.remove('hidden');
                    } else {
                        suggestionsDropdown.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching suggestions:', error);
                    this.hideLoading(loadingElement);
                    this.isSuggesting = false;
                    suggestionsDropdown.classList.add('hidden');
                });
        }, 300);
    }

    handleSearch(query, resultsList, resultsDropdown, loadingElement) {
        clearTimeout(this.searchTimeout);
        
        this.searchTimeout = setTimeout(() => {
            this.performSearch(query, resultsList, resultsDropdown, loadingElement);
        }, 500);
    }

    performSearch(query, resultsList, resultsDropdown, loadingElement) {
        if (this.isSearching) return;
        
        this.isSearching = true;
        this.showLoading(loadingElement);
        
        fetch(`/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                this.hideLoading(loadingElement);
                this.isSearching = false;
                
                if (data.success && data.data.length > 0) {
                    this.renderSearchResults(data.data, resultsList);
                    resultsDropdown.classList.remove('hidden');
                } else {
                    this.renderNoResults(resultsList);
                    resultsDropdown.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error performing search:', error);
                this.hideLoading(loadingElement);
                this.isSearching = false;
                this.renderError(resultsList);
                resultsDropdown.classList.remove('hidden');
            });
    }

    renderSuggestions(suggestions, container) {
        container.innerHTML = '';
        
        suggestions.forEach(suggestion => {
            const item = document.createElement('div');
            item.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm';
            item.innerHTML = `
                <div class="flex items-center">
                    <span class="text-gray-600">${this.getSuggestionIcon(suggestion.type)}</span>
                    <span class="ml-2">${this.escapeHtml(suggestion.text)}</span>
                </div>
            `;
            
            item.addEventListener('click', () => {
                this.selectSuggestion(suggestion.text);
            });
            
            container.appendChild(item);
        });
    }

    renderSearchResults(results, container) {
        container.innerHTML = '';
        
        results.forEach(result => {
            const item = document.createElement('div');
            item.className = 'px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0';
            
            const priceHtml = result.has_discount ? 
                `<div class="flex items-center space-x-2">
                    <span class="text-lg font-semibold text-red-600">${result.discount_price}đ</span>
                    <span class="text-sm text-gray-500 line-through">${result.price}đ</span>
                    <span class="text-xs bg-red-100 text-red-600 px-1 rounded">-${result.discount_percent}%</span>
                </div>` :
                `<span class="text-lg font-semibold text-gray-900">${result.price}đ</span>`;
            
            item.innerHTML = `
                <div class="flex items-start space-x-3">
                    <img src="${result.image_url}" alt="${this.escapeHtml(result.title)}" 
                         class="w-12 h-16 object-cover rounded">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-medium text-gray-900 truncate">${this.escapeHtml(result.title)}</h3>
                        <div class="text-sm text-gray-600 mt-1">
                            <div>Tác giả: ${this.escapeHtml(result.author)}</div>
                            <div>Nhà xuất bản: ${this.escapeHtml(result.publisher)}</div>
                            <div>Danh mục: ${this.escapeHtml(result.category)}</div>
                        </div>
                        ${priceHtml}
                    </div>
                </div>
            `;
            
            item.addEventListener('click', () => {
                window.location.href = result.url;
            });
            
            container.appendChild(item);
        });
        
        // Add "Xem tất cả kết quả" link
        const viewAllItem = document.createElement('div');
        viewAllItem.className = 'px-4 py-2 text-center bg-gray-50 hover:bg-gray-100 cursor-pointer';
        viewAllItem.innerHTML = `
            <span class="text-sm text-blue-600 font-medium">Xem tất cả kết quả cho "${document.getElementById('search-input')?.value || document.getElementById('mobile-search-input')?.value}"</span>
        `;
        viewAllItem.addEventListener('click', () => {
            const query = document.getElementById('search-input')?.value || document.getElementById('mobile-search-input')?.value;
            window.location.href = `/search-results?q=${encodeURIComponent(query)}`;
        });
        container.appendChild(viewAllItem);
    }

    renderNoResults(container) {
        container.innerHTML = `
            <div class="px-4 py-8 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="text-lg font-medium mb-2">Không tìm thấy kết quả</p>
                <p class="text-sm">Hãy thử với từ khóa khác hoặc kiểm tra chính tả</p>
            </div>
        `;
    }

    renderError(container) {
        container.innerHTML = `
            <div class="px-4 py-8 text-center text-red-500">
                <svg class="mx-auto h-12 w-12 text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-lg font-medium mb-2">Có lỗi xảy ra</p>
                <p class="text-sm">Vui lòng thử lại sau</p>
            </div>
        `;
    }

    selectSuggestion(text) {
        const desktopInput = document.getElementById('search-input');
        const mobileInput = document.getElementById('mobile-search-input');
        
        if (desktopInput) {
            desktopInput.value = text;
            desktopInput.focus();
        }
        if (mobileInput) {
            mobileInput.value = text;
            mobileInput.focus();
        }
        
        this.hideAllDropdowns();
    }

    getSuggestionIcon(type) {
        const icons = {
            'book': '📖',
            'author': '✍️',
            'publisher': '🏢',
            'category': '📚'
        };
        return icons[type] || '🔍';
    }

    showLoading(element) {
        if (element) {
            element.classList.remove('hidden');
        }
    }

    hideLoading(element) {
        if (element) {
            element.classList.add('hidden');
        }
    }

    hideDropdowns(suggestionsDropdown, resultsDropdown) {
        if (suggestionsDropdown) suggestionsDropdown.classList.add('hidden');
        if (resultsDropdown) resultsDropdown.classList.add('hidden');
    }

    hideAllDropdowns() {
        const dropdowns = [
            'search-suggestions', 'search-results',
            'mobile-search-suggestions', 'mobile-search-results'
        ];
        
        dropdowns.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.classList.add('hidden');
            }
        });
    }

    initClickOutside() {
        document.addEventListener('click', (e) => {
            const searchContainers = [
                document.querySelector('.relative.flex.w-full.max-w-2xl'),
                document.querySelector('#mobile-menu .relative')
            ];
            
            let isInsideSearch = false;
            searchContainers.forEach(container => {
                if (container && container.contains(e.target)) {
                    isInsideSearch = true;
                }
            });
            
            if (!isInsideSearch) {
                this.hideAllDropdowns();
            }
        });
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize search functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new SearchManager();
});
