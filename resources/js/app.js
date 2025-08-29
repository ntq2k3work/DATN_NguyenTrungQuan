import './bootstrap';
import Toastify from 'toastify-js';
import 'toastify-js/src/toastify.css';

// Make Toastify available globally
window.Toastify = Toastify;

// Toast notification functions
const showToast = (message, type = 'success') => {
    const backgroundColor = type === 'success' ? '#10B981' :
                         type === 'error' ? '#EF4444' :
                         type === 'warning' ? '#F59E0B' : '#3B82F6';

    Toastify({
        text: message,
        duration: 4000,
        gravity: "top",
        position: "right",
        backgroundColor: backgroundColor,
        stopOnFocus: true,
        className: "rounded-lg shadow-lg",
        close: true,
        style: {
            fontFamily: 'system-ui, -apple-system, sans-serif',
            fontSize: '14px',
            fontWeight: '500'
        }
    }).showToast();
};

// Make toast functions available globally
window.showToast = showToast;
window.showSuccessToast = (message) => showToast(message, 'success');
window.showErrorToast = (message) => showToast(message, 'error');
window.showWarningToast = (message) => showToast(message, 'warning');
window.showInfoToast = (message) => showToast(message, 'info');
