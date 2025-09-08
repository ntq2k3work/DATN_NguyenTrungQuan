// Global wishlist functionality
window.WishlistManager = {
    // Toggle wishlist status
    toggleWishlist: function(bookId) {
        const button = document.querySelector(`[data-book-id="${bookId}"]`);
        if (!button) return;

        const svg = button.querySelector('svg');
        const originalText = button.getAttribute('title');
        
        // Disable button and show loading
        button.disabled = true;
        button.setAttribute('title', 'Đang xử lý...');
        button.classList.add('opacity-50');
        
        // Make API call
        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                book_id: bookId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update button state
                const isInWishlist = data.in_wishlist;
                
            if (isInWishlist) {
                // Added to wishlist
                button.classList.remove('opacity-0', 'group-hover:opacity-100');
                button.classList.add('opacity-100', 'bg-red-50', 'hover:bg-red-100');
                svg.classList.remove('stroke-current', 'text-gray-600', 'hover:text-red-600');
                svg.classList.add('fill-current', 'text-red-600');
                svg.setAttribute('fill', 'currentColor');
                svg.setAttribute('stroke', 'none');
                button.setAttribute('title', 'Xóa khỏi yêu thích');
                button.setAttribute('data-in-wishlist', 'true');
                
                this.showToast(data.message, 'success');
                
                // Update wishlist count in header
                this.updateWishlistCount(data.wishlist_count);
            } else {
                // Removed from wishlist
                button.classList.remove('opacity-100', 'bg-red-50', 'hover:bg-red-100');
                button.classList.add('opacity-0', 'group-hover:opacity-100');
                svg.classList.remove('fill-current', 'text-red-600');
                svg.classList.add('stroke-current', 'text-gray-600', 'hover:text-red-600');
                svg.setAttribute('fill', 'none');
                svg.setAttribute('stroke', 'currentColor');
                button.setAttribute('title', 'Thêm vào yêu thích');
                button.setAttribute('data-in-wishlist', 'false');
                
                this.showToast(data.message, 'success');
                
                // Update wishlist count in header
                this.updateWishlistCount(data.wishlist_count);
            }
            } else {
                this.showToast(data.error || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showToast('Có lỗi xảy ra khi thực hiện thao tác', 'error');
        })
        .finally(() => {
            // Re-enable button
            button.disabled = false;
            button.classList.remove('opacity-50');
        });
    },

    // Update wishlist count in header
    updateWishlistCount: function(count) {
        const wishlistCountElement = document.querySelector('.wishlist-count');
        if (wishlistCountElement) {
            wishlistCountElement.textContent = count;
        }
    },

    // Show toast notification
    showToast: function(message, type = 'success') {
        // Create toast if it doesn't exist
        let toast = document.getElementById('toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'toast';
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50';
            toast.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span id="toast-message">${message}</span>
                </div>
            `;
            document.body.appendChild(toast);
        }
        
        const toastMessage = document.getElementById('toast-message');
        toastMessage.textContent = message;
        
        // Change color based on type
        if (type === 'error') {
            toast.classList.remove('bg-green-500');
            toast.classList.add('bg-red-500');
        } else {
            toast.classList.remove('bg-red-500');
            toast.classList.add('bg-green-500');
        }
        
        // Show toast
        toast.classList.remove('translate-x-full');
        
        // Hide toast after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
        }, 3000);
    },

    // Check wishlist status for multiple books
    checkWishlistStatus: function(bookIds) {
        if (!Array.isArray(bookIds) || bookIds.length === 0) return;

        fetch('/wishlist/check', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                book_ids: bookIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update all wishlist buttons based on status
                bookIds.forEach(bookId => {
                    const button = document.querySelector(`[data-book-id="${bookId}"]`);
                    if (button && data.wishlist_status && data.wishlist_status[bookId]) {
                        const svg = button.querySelector('svg');
                        button.classList.remove('opacity-0', 'group-hover:opacity-100');
                        button.classList.add('opacity-100', 'bg-red-50', 'hover:bg-red-100');
                        svg.classList.remove('stroke-current', 'text-gray-600', 'hover:text-red-600');
                        svg.classList.add('fill-current', 'text-red-600');
                        svg.setAttribute('fill', 'currentColor');
                        svg.setAttribute('stroke', 'none');
                        button.setAttribute('title', 'Xóa khỏi yêu thích');
                        button.setAttribute('data-in-wishlist', 'true');
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error checking wishlist status:', error);
        });
    }
};

// Global function for backward compatibility
function toggleWishlist(bookId) {
    window.WishlistManager.toggleWishlist(bookId);
}
