<!-- Toast Container Component -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1060;" id="toastContainer">
  <!-- Toasts will be inserted here dynamically -->
</div>

<script>
// Global Toast Manager - Available throughout the application
if (typeof window.ToastManager === 'undefined') {
  window.ToastManager = {
    container: null,

    init: function() {
      this.container = document.getElementById('toastContainer');
      if (!this.container) {
        console.warn('Toast container not found. Make sure to include the toast-container component.');
        return false;
      }
      return true;
    },

    show: function(title, message, type = 'info', duration = 5000) {
      if (!this.container && !this.init()) return;

      const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);    // Icon mapping for different types
    const iconMap = {
      success: 'ti tabler-check',
      error: 'ti tabler-x',
      warning: 'ti tabler-alert-triangle',
      info: 'ti tabler-info-circle',
      primary: 'ti tabler-bell'
    };

    // Color mapping for different types
    const colorMap = {
      success: 'text-success',
      error: 'text-danger',
      warning: 'text-warning',
      info: 'text-info',
      primary: 'text-primary'
    };

    const icon = iconMap[type] || iconMap.info;
    const iconColor = colorMap[type] || colorMap.info;

      const toastHtml = `
        <div class="bs-toast toast toast-ex fade"
             role="alert" aria-live="assertive" aria-atomic="true"
             data-bs-delay="${duration}" id="${toastId}">
          <div class="toast-header">
            <i class="icon-base ${icon} icon-xs me-2 ${iconColor}"></i>
            <div class="me-auto fw-medium">${this.escapeHtml(title)}</div>
            <small class="text-body-secondary">now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">${this.escapeHtml(message)}</div>
        </div>
      `;    // Insert toast into container
    this.container.insertAdjacentHTML('beforeend', toastHtml);

    // Get the toast element and show it
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
      delay: duration
    });

    // Show the toast
    toast.show();

    // Remove toast from DOM after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
      toastElement.remove();
    });

    return toast;
  },

  success: function(title, message, duration = 5000) {
    return this.show(title, message, 'success', duration);
  },

  error: function(title, message, duration = 7000) {
    return this.show(title, message, 'error', duration);
  },

  warning: function(title, message, duration = 6000) {
    return this.show(title, message, 'warning', duration);
  },

  info: function(title, message, duration = 5000) {
    return this.show(title, message, 'info', duration);
  },

  primary: function(title, message, duration = 5000) {
    return this.show(title, message, 'primary', duration);
  },

  clear: function() {
    if (!this.container) return;

    const toasts = this.container.querySelectorAll('.toast');
    toasts.forEach(toast => {
      const bsToast = bootstrap.Toast.getInstance(toast);
      if (bsToast) {
        bsToast.hide();
      }
    });
  },

  // Utility function to escape HTML
  escapeHtml: function(text) {
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
  }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
  window.ToastManager.init();
});

// Legacy function for backward compatibility
window.showToast = function(title, message, type = 'info') {
  window.ToastManager.show(title, message, type);
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
  window.ToastManager.init();
});
}
</script>
<style>
/* Toast styles */
/* Toast specific styles */
.toast-container {
  z-index: 9999 !important;
  position: fixed !important;
  top: 20px !important;
  right: 20px !important;
}

.bs-toast {
  min-width: 300px;
  z-index: 10000 !important;
}

.toast-ex {
  animation: fadeInDown 0.5s ease-out;
}

@keyframes fadeInDown {
  0% {
    opacity: 0;
    transform: translate3d(0, -100%, 0);
  }
  100% {
    opacity: 1;
    transform: translate3d(0, 0, 0);
  }
}

.icon-xs {
  width: 16px;
  height: 16px;
}
</style>
