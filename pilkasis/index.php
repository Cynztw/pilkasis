<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=yes">
    <meta name="description" content="Pilkasis - Sistem Pemilihan Ketua OSIS berbasis web dengan interface yang user-friendly">
    <meta name="keywords" content="pilkasis, osis, voting, pemilihan, sistem">
    <meta name="author" content="Pilkasis Team">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Pilkasis">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#764ba2">
    <meta name="msapplication-TileColor" content="#764ba2">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="email=no">
    <meta name="color-scheme" content="light dark">
    
    <title>Pilkasis - Pemilihan Ketua OSIS</title>
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="public/manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="public/icons/icon-192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="public/icons/icon-512.png">
    <link rel="apple-touch-icon" href="public/icons/icon-192.png">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Mobile Optimization CSS -->
    <link rel="stylesheet" href="public/css/mobile.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        .navbar-brand {
            font-weight: bold;
            color: #764ba2 !important;
            font-size: 1.5rem;
        }
        .welcome-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            background: white;
            margin-top: 40px;
        }
        .welcome-header {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            padding: 60px 40px;
            text-align: center;
        }
        .welcome-header h1 {
            font-weight: bold;
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .welcome-header .subtitle {
            font-size: 1.3rem;
            opacity: 0.95;
            margin-bottom: 10px;
        }
        .welcome-body {
            padding: 50px 40px;
            text-align: center;
        }
        .user-info {
            margin: 30px 0;
            font-size: 1.1rem;
        }
        .user-name {
            color: #764ba2;
            font-weight: bold;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        .user-role {
            display: inline-block;
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            margin-top: 10px;
        }
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        .btn-action {
            padding: 12px 30px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-voting {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-voting:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-admin {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 87, 108, 0.4);
            color: white;
        }
        .btn-logout {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(250, 112, 154, 0.4);
            color: white;
        }
        .feature-info {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            border-left: 5px solid #764ba2;
            margin: 30px 0;
            text-align: left;
        }
        .feature-info strong {
            color: #764ba2;
        }
        
        /* ===== LOADING SCREEN STYLES ===== */
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
        
        .loading-logo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: white;
            padding: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            object-fit: contain;
            margin: 0 auto;
            animation: logoPulse 1.5s ease-in-out infinite;
        }
        
        @keyframes logoPulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.9;
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
</head>
<body>
    <!-- Loading Screen -->
    <div id="loading-screen" class="loading-screen">
        <div class="loading-container">
            <div class="loading-icon">
                <img src="public/images/pramuka-logo.png" alt="Logo" class="loading-logo">
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
    
    <nav class="navbar navbar-light navbar-custom sticky-top">
        <div class="container">
            <span class="navbar-brand"><i class="bi bi-check2-square"></i> Pilkasis</span>
            <span class="text-dark"><small>Pemilihan Ketua OSIS</small></span>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="welcome-card">
                    <div class="welcome-header">
                        <h1><i class="bi bi-hand-thumbs-up"></i> Selamat Datang</h1>
                        <p class="subtitle">di Sistem Pemilihan Ketua OSIS</p>
                    </div>
                    <div class="welcome-body">
                        <div class="user-info">
                            <p>Halo,</p>
                            <div class="user-name"><?php echo htmlspecialchars($_SESSION['nama']); ?></div>
                        </div>
                        
                        <div class="user-role">
                            <i class="bi bi-person-badge"></i> 
                            <?php
                            $role = ucfirst($_SESSION['role']);
                            if ($_SESSION['role'] === 'siswa') {
                                echo 'Siswa (1 Poin)';
                            } elseif ($_SESSION['role'] === 'guru') {
                                echo 'Guru (2 Poin)';
                            } else {
                                echo $role . ' (Admin)';
                            }
                            ?>
                        </div>

                        <div class="feature-info">
                            <strong><i class="bi bi-info-circle"></i> Informasi Voting:</strong><br>
                            Setiap siswa memiliki 1 poin suara, sedangkan guru memiliki 2 poin suara untuk calon ketua OSIS.
                        </div>

                        <div class="button-group">
                            <button id="install-btn" class="btn btn-primary btn-lg" style="display: none; min-height: 44px;">
                                <i class="bi bi-download"></i> Instal ke HP
                            </button>
                            <?php if ($_SESSION['role'] === 'siswa'): ?>
                                <a href="views/siswa/voting.php" class="btn-action btn-voting">
                                    <i class="bi bi-hand-thumbs-up"></i> Mulai Voting
                                </a>
                            <?php elseif ($_SESSION['role'] === 'guru'): ?>
                                <a href="views/guru/voting.php" class="btn-action btn-voting">
                                    <i class="bi bi-hand-thumbs-up"></i> Mulai Voting
                                </a>
                            <?php elseif ($_SESSION['role'] === 'admin'): ?>
                                <a href="views/admin/dashboard.php" class="btn-action btn-admin">
                                    <i class="bi bi-speedometer2"></i> Dashboard Admin
                                </a>
                            <?php endif; ?>
                            
                            <a href="logout.php" class="btn-action btn-logout">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- PWA & Mobile App Scripts -->
    <script src="public/js/app.js"></script>
    
    <!-- Loading Screen Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingScreen = document.getElementById('loading-screen');
            if (loadingScreen) {
                setTimeout(() => {
                    loadingScreen.classList.add('hidden');
                }, 2000);  // Mulai hide setelah 2 detik
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 2500);  // Total 2.5 detik loading screen
            }
        });
        
        // Show loading saat navigasi
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && !link.target && !link.href.startsWith('#') && 
                link.href.includes('pilkasis') && !link.getAttribute('data-no-loading')) {
                const loadingScreen = document.getElementById('loading-screen');
                if (loadingScreen) {
                    loadingScreen.style.display = 'flex';
                    loadingScreen.classList.remove('hidden');
                }
            }
        });
    </script>
</body>
</html>