// Service Worker Registration
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker
      .register('/webprosm2/pilkasis/public/js/service-worker.js')
      .then((registration) => {
        console.log('✓ Service Worker terdaftar:', registration);
      })
      .catch((error) => {
        console.log('✗ Service Worker registrasi gagal:', error);
      });
  });
}

// Install Prompt
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  deferredPrompt = e;
  showInstallPrompt();
});

function showInstallPrompt() {
  const installButton = document.getElementById('install-btn');
  if (installButton) {
    installButton.style.display = 'block';
    installButton.addEventListener('click', async () => {
      if (deferredPrompt) {
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        console.log(`User response: ${outcome}`);
        deferredPrompt = null;
        installButton.style.display = 'none';
      }
    });
  }
}

window.addEventListener('appinstalled', () => {
  console.log('✓ PWA berhasil diinstall');
  const installBtn = document.getElementById('install-btn');
  if (installBtn) {
    installBtn.style.display = 'none';
  }
});

// Mobile Navigation Enhancement
document.addEventListener('DOMContentLoaded', () => {
  // Add touch feedback to buttons
  const buttons = document.querySelectorAll('button, a.btn');
  buttons.forEach((btn) => {
    btn.addEventListener('touchstart', function () {
      this.style.opacity = '0.7';
    });
    btn.addEventListener('touchend', function () {
      this.style.opacity = '1';
    });
  });

  // Prevent zoom on input focus (iOS)
  document.addEventListener('touchmove', function (e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
      return;
    }
  });

  // Initialize tooltips for mobile
  if ('ontouchstart' in window) {
    document.body.classList.add('is-mobile');
  }
});

// Check online/offline status
window.addEventListener('online', () => {
  console.log('✓ Online');
  showNotification('Terhubung ke internet', 'success');
});

window.addEventListener('offline', () => {
  console.log('✗ Offline');
  showNotification('Mode offline - Fitur terbatas', 'warning');
});

// Notification helper
function showNotification(message, type = 'info') {
  // Using Bootstrap alert component
  const alertDiv = document.createElement('div');
  alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x`;
  alertDiv.style.zIndex = '9999';
  alertDiv.style.marginTop = '10px';
  alertDiv.role = 'alert';
  alertDiv.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  `;

  document.body.insertBefore(alertDiv, document.body.firstChild);

  setTimeout(() => {
    alertDiv.remove();
  }, 3000);
}

// Fullscreen on tap (optional)
function requestFullscreen() {
  const elem = document.documentElement;
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.webkitRequestFullscreen) {
    elem.webkitRequestFullscreen();
  } else if (elem.mozRequestFullScreen) {
    elem.mozRequestFullScreen();
  } else if (elem.msRequestFullscreen) {
    elem.msRequestFullscreen();
  }
}

// Export for use
window.AppUtils = {
  showNotification,
  requestFullscreen,
};
