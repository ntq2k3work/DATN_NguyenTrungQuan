// Global AJAX functionality and toast notifications for Livewire components
document.addEventListener('livewire:init', () => {
    // Listen for cart updates
    Livewire.on('addToCart', (event) => {
        const bookId = event.bookId;

        // Show loading state
        const buttons = document.querySelectorAll(`[data-book-id="${bookId}"]`);
        buttons.forEach(button => {
            if (button.classList.contains('add-to-cart-btn')) {
                const originalText = button.textContent;
                button.disabled = true;
                button.textContent = 'Đang thêm...';
                button.classList.add('opacity-50');

                // Reset after 2 seconds
                setTimeout(() => {
                    button.disabled = false;
                    button.textContent = originalText;
                    button.classList.remove('opacity-50');
                }, 2000);
            }
        });

        // Call the cart manager's addToCart method
        Livewire.dispatch('addToCart', { bookId: bookId }, 'cart-manager');
    });

    // Listen for wishlist updates
    Livewire.on('toggleWishlist', (event) => {
        const bookId = event.bookId;

        // Call the wishlist manager's toggleWishlist method
        Livewire.dispatch('toggleWishlist', { bookId: bookId }, 'wishlist-manager');
    });

    // Listen for toast notifications
    Livewire.on('show-toast', (event) => {
        showToast(event.message, event.type);
    });

    // Listen for cart count updates
    Livewire.on('cartCountUpdated', (event) => {
        updateCartCount(event.count);
    });

    // Listen for wishlist count updates
    Livewire.on('wishlistCountUpdated', (event) => {
        updateWishlistCount(event.count);
    });
});

// Toast notification function
function showToast(message, type = 'success') {
    // Remove existing toast
    const existingToast = document.getElementById('toast');
    if (existingToast) {
        existingToast.remove();
    }

    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };

    const icons = {
        success: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>`,
        error: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                 <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
               </svg>`,
        warning: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                  </svg>`,
        info: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                 <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
               </svg>`
    };

    const toast = document.createElement('div');
    toast.id = 'toast';
    toast.className = `${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform transition-all duration-300 ease-in-out translate-x-full opacity-0 fixed top-4 right-4 z-50`;
    toast.innerHTML = `
        <div class="flex-shrink-0">
            ${icons[type]}
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium">${message}</p>
        </div>
        <button onclick="removeToast()" class="flex-shrink-0 ml-4 text-white hover:text-gray-200">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;

    document.body.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    }, 100);

    // Auto remove after 5 seconds
    setTimeout(() => {
        removeToast();
    }, 5000);
}

function removeToast() {
    const toast = document.getElementById('toast');
    if (toast) {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}

// Update cart count in header
function updateCartCount(count) {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

// Update wishlist count in header
function updateWishlistCount(count) {
    const wishlistCountElement = document.querySelector('.wishlist-count');
    if (wishlistCountElement) {
        wishlistCountElement.textContent = count;
    }
}

// Format price function
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}
