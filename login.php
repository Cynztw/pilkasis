<?php
session_start();
require_once 'config/Database.php';
require_once 'classes/User.php';

$db = new Database();
$conn = $db->connect();
$user = new User($conn);

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $login = $user->login($username, $password);
    if ($login) {
        $_SESSION['user_id'] = $login['id'];
        $_SESSION['username'] = $login['username'];
        $_SESSION['nama'] = $login['nama'];
        $_SESSION['role'] = $login['role'];
        
        if ($login['role'] == 'admin') {
            header('Location: views/admin/dashboard.php');
        } elseif ($login['role'] == 'guru') {
            header('Location: views/guru/voting.php');
        } else {
            header('Location: views/siswa/voting.php');
        }
        exit;
    } else {
        $message = 'Username atau password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=yes">
    <meta name="description" content="Pilkasis - Sistem Pemilihan Ketua OSIS">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Pilkasis">
    <meta name="theme-color" content="#764ba2">
    <title>Login - Pilkasis</title>
    <link rel="manifest" href="public/manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="public/icons/icon-192.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/css/mobile.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .login-header h2 {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .login-body {
            padding: 40px;
            background: white;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .alert-custom {
            border-radius: 10px;
            border-left: 5px solid #dc3545;
            background-color: #fff5f5;
        }
        .login-footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #e0e0e0;
            background: #f9f9f9;
        }
        .login-footer a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        /* ===== LOADING SCREEN ===== */
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
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.9; }
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
        }
        
        .loading-bar {
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            animation: slideBar 1.5s infinite;
            border-radius: 10px;
        }
        
        @keyframes slideBar {
            0% { transform: translateX(-200px); }
            100% { transform: translateX(200px); }
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
        
        .dot:nth-child(1) { animation-delay: 0s; }
        .dot:nth-child(2) { animation-delay: 0.2s; }
        .dot:nth-child(3) { animation-delay: 0.4s; }
        
        @keyframes dotBounce {
            0%, 100% { opacity: 0.4; transform: translateY(0); }
            50% { opacity: 1; transform: translateY(-10px); }
        }
        
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .loading-screen.hidden {
            animation: fadeOut 0.5s ease-out forwards;
        }
        
        @keyframes fadeOut {
            to { opacity: 0; visibility: hidden; }
        }
        
        @media (max-width: 600px) {
            .loading-icon { font-size: 60px; }
            .loading-title { font-size: 24px; }
            .loading-subtitle { font-size: 12px; }
            .loading-bar-container { width: 150px; }
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
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-card">
                    <div class="login-header">
                        <h2><i class="bi bi-check-circle"></i> Pilkasis</h2>
                        <p class="mb-0">Sistem Pemilihan Ketua OSIS</p>
                    </div>
                    <div class="login-body">
                        <?php if ($message): ?>
                            <div class="alert alert-custom alert-danger" role="alert">
                                <i class="bi bi-exclamation-circle"></i> <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label"><i class="bi bi-person"></i> Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label"><i class="bi bi-lock"></i> Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn-login"><i class="bi bi-box-arrow-in-right"></i> Login</button>
                        </form>
                    </div>
                    <div class="login-footer">
                        Belum punya akun? <a href="register.php"><i class="bi bi-pencil-square"></i> Daftar di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && !link.target && !link.href.startsWith('#')) {
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