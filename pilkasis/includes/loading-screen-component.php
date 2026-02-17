<?php
/**
 * LOADING SCREEN INJECTOR
 * Include file ini di <body> tag (awal) untuk menambahkan loading screen
 * 
 * Usage dalam halaman:
 * <body>
 *     <?php include 'includes/loading-screen-component.php'; ?>
 *     <!-- rest of content -->
 * </body>
 */
?>

<!-- Loading Screen HTML -->
<div id="loading-screen" class="loading-screen">
    <div class="loading-container">
        <div class="loading-icon">
            <i class="bi bi-check2-square"></i>
        </div>
        <h2 class="loading-title">PILKASIS</h2>
        <p class="loading-subtitle">Pemilihan Ketua OSIS</p>
        <div class="loading-bar-container">
            <div class="loading-bar"></div>
        </div>
        <p class="loading-text">Sedang memuat...</p>
        <div class="loading-status">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</div>

<!-- Loading Screen Styles (masukkan di <head>) -->
<style>
.loading-screen {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 99999;
    width: 100%;
    height: 100vh;
    overflow: hidden;
}

.loading-container {
    text-align: center;
    color: white;
    position: relative;
}

.loading-icon {
    font-size: 80px;
    margin-bottom: 20px;
    animation: iconPulse 1.5s ease-in-out infinite;
}

@keyframes iconPulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.8;
    }
}

.loading-title {
    font-size: 32px;
    font-weight: bold;
    margin: 0 0 5px 0;
    letter-spacing: 2px;
    animation: fadeInDown 0.6s ease-out;
}

.loading-subtitle {
    font-size: 14px;
    margin: 0 0 30px 0;
    opacity: 0.9;
    animation: fadeInUp 0.6s ease-out 0.2s;
    animation-fill-mode: both;
}

.loading-bar-container {
    width: 200px;
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    margin: 30px auto;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
}

.loading-bar {
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
    animation: slideBar 1.5s infinite;
    border-radius: 10px;
}

@keyframes slideBar {
    0% {
        transform: translateX(-200px);
    }
    100% {
        transform: translateX(200px);
    }
}

.loading-text {
    font-size: 16px;
    margin: 20px 0;
    opacity: 0.9;
    animation: fadeInUp 0.6s ease-out 0.4s;
    animation-fill-mode: both;
}

.loading-status {
    margin-top: 30px;
    display: flex;
    justify-content: center;
    gap: 8px;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
    opacity: 0.6;
    animation: dotBounce 1.4s infinite;
}

.dot:nth-child(1) {
    animation-delay: 0s;
}

.dot:nth-child(2) {
    animation-delay: 0.2s;
}

.dot:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes dotBounce {
    0%, 100% {
        opacity: 0.4;
        transform: translateY(0);
    }
    50% {
        opacity: 1;
        transform: translateY(-10px);
    }
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.loading-screen.hidden {
    animation: fadeOut 0.5s ease-out forwards;
}

@keyframes fadeOut {
    to {
        opacity: 0;
        visibility: hidden;
    }
}

@media (max-width: 600px) {
    .loading-icon {
        font-size: 60px;
        margin-bottom: 15px;
    }
    
    .loading-title {
        font-size: 24px;
        letter-spacing: 1px;
    }
    
    .loading-subtitle {
        font-size: 12px;
        margin-bottom: 20px;
    }
    
    .loading-bar-container {
        width: 150px;
        margin: 20px auto;
    }
    
    .loading-text {
        font-size: 14px;
    }
    
    .dot {
        width: 6px;
        height: 6px;
    }
}
</style>

<!-- Loading Screen JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadingScreen = document.getElementById('loading-screen');
    if (loadingScreen) {
        setTimeout(() => {
            loadingScreen.classList.add('hidden');
        }, 300);
        setTimeout(() => {
            loadingScreen.style.display = 'none';
        }, 800);
    }
});

document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (link && !link.target && !link.href.startsWith('#') && 
        !link.getAttribute('data-no-loading')) {
        const loadingScreen = document.getElementById('loading-screen');
        if (loadingScreen) {
            loadingScreen.style.display = 'flex';
            loadingScreen.classList.remove('hidden');
        }
    }
});

window.loadingScreen = {
    show: function() {
        const ls = document.getElementById('loading-screen');
        if (ls) {
            ls.style.display = 'flex';
            ls.classList.remove('hidden');
        }
    },
    hide: function() {
        const ls = document.getElementById('loading-screen');
        if (ls) {
            ls.classList.add('hidden');
        }
    }
};
</script>
