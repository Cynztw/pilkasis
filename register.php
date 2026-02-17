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
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];

    if ($user->register($username, $password, $nama, $kelas)) {
        $message = 'Registrasi berhasil! <a href="login.php">Login di sini</a>';
    } else {
        $message = 'Username sudah digunakan!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=yes">
    <meta name="description" content="Register - Pilkasis Voting System">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Pilkasis">
    <meta name="theme-color" content="#764ba2">
    <meta name="color-scheme" content="light dark">
    
    <title>Register - Pilkasis</title>
    
    <link rel="manifest" href="public/manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="public/icons/icon-192.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/css/mobile.css">
    
    <style>
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

        .loading-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: iconPulse 1.5s ease-in-out infinite;
        }

        .loading-logo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: white;
            padding: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            object-fit: contain;
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
            animation: fadeInUp 0.6s ease-out 0.2s both;
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
        }

        @keyframes slideBar {
            0% { transform: translateX(-200px); }
            100% { transform: translateX(200px); }
        }

        .loading-screen.hidden {
            animation: fadeOut 0.5s ease-out forwards;
        }

        @keyframes fadeOut {
            to { opacity: 0; visibility: hidden; }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== MAIN STYLES ===== */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        .register-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .register-header {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .register-header h2 {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .register-body {
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
            border-color: #764ba2;
            box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.25);
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .btn-register {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(118, 75, 162, 0.4);
            color: white;
        }
        .alert-success-custom {
            border-radius: 10px;
            border-left: 5px solid #28a745;
            background-color: #f0fff4;
        }
        .alert-danger-custom {
            border-radius: 10px;
            border-left: 5px solid #dc3545;
            background-color: #fff5f5;
        }
        .register-footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #e0e0e0;
            background: #f9f9f9;
        }
        .register-footer a {
            color: #764ba2;
            font-weight: 600;
            text-decoration: none;
        }
        .register-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-container">
            <div class="loading-icon">
                <img src="public/images/pramuka-logo.png" alt="Logo" class="loading-logo">
            </div>
            <div class="loading-title">PILKASIS</div>
            <div class="loading-subtitle">Registrasi Akun</div>
            <div class="loading-bar-container">
                <div class="loading-bar"></div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="register-card">
                    <div class="register-header">
                        <h2><i class="bi bi-person-plus"></i> Daftar Sekarang</h2>
                        <p class="mb-0">Buat akun baru untuk voting</p>
                    </div>
                    <div class="register-body">
                        <?php if ($message): ?>
                            <?php if (strpos($message, 'berhasil') !== false): ?>
                                <div class="alert alert-success-custom alert-success" role="alert">
                                    <i class="bi bi-check-circle"></i> <?php echo $message; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-danger-custom alert-danger" role="alert">
                                    <i class="bi bi-exclamation-circle"></i> <?php echo $message; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="nama" class="form-label"><i class="bi bi-person"></i> Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label"><i class="bi bi-bookmark"></i> Kelas</label>
                                <input type="text" class="form-control" id="kelas" name="kelas" placeholder="Contoh: X-A" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label"><i class="bi bi-at"></i> Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label"><i class="bi bi-lock"></i> Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn-register"><i class="bi bi-check-circle"></i> Daftar</button>
                        </form>
                    </div>
                    <div class="register-footer">
                        Sudah punya akun? <a href="login.php"><i class="bi bi-box-arrow-in-right"></i> Login di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Loading Screen Timing
        window.addEventListener('load', function() {
            const loadingScreen = document.getElementById('loadingScreen');
            setTimeout(() => {
                loadingScreen.classList.add('hidden');
            }, 2000);
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 2500);
        });

        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('public/js/service-worker.js').catch(() => {});
        }
    </script>
</body>
</html>