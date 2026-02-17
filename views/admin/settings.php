<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../login.php');
    exit;
}

require_once '../../config/Database.php';
require_once '../../classes/Settings.php';

$db = new Database();
$conn = $db->connect();
$settings = new Settings($conn);

$message = '';
$message_type = ''; // 'success' or 'danger'
$current_settings = null;

// Ambil pengaturan saat ini
$query = "SELECT * FROM settings LIMIT 1";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $current_settings = $result->fetch_assoc();
}

// Proses update pengaturan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status_pemilihan'];
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? null;
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? null;

    if ($current_settings) {
        $query = "UPDATE settings SET status_pemilihan = ?, tanggal_mulai = ?, tanggal_selesai = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $status, $tanggal_mulai, $tanggal_selesai, $current_settings['id']);
    } else {
        $query = "INSERT INTO settings (status_pemilihan, tanggal_mulai, tanggal_selesai) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $status, $tanggal_mulai, $tanggal_selesai);
    }

    if ($stmt->execute()) {
        $message = ($status === 'open' ? 'Voting dibuka!' : 'Voting ditutup!') . ' Pengaturan berhasil disimpan.';
        $message_type = 'success';
        // Refresh settings
        $result = $conn->query("SELECT * FROM settings LIMIT 1");
        if ($result->num_rows > 0) {
            $current_settings = $result->fetch_assoc();
        }
    } else {
        $message = 'Gagal memperbarui pengaturan: ' . $stmt->error;
        $message_type = 'danger';
    }
}

$status = $current_settings['status_pemilihan'] ?? 'closed';
$tanggal_mulai = $current_settings['tanggal_mulai'] ?? '';
$tanggal_selesai = $current_settings['tanggal_selesai'] ?? '';

// Calculate countdown
$countdown = '';
if ($tanggal_selesai) {
    $selesai = new DateTime($tanggal_selesai);
    $now = new DateTime();
    $interval = $now->diff($selesai);
    
    if ($selesai > $now) {
        $days = $interval->days;
        $hours = $interval->h;
        $mins = $interval->i;
        $countdown = ($days > 0 ? "$days hari " : '') . ($hours > 0 ? "$hours jam " : '') . "$mins menit";
    } else {
        $countdown = "Telah berakhir";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=yes">
    <meta name="description" content="Pengaturan Voting - Pilkasis Admin Panel">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Pilkasis">
    <meta name="theme-color" content="#764ba2">
    <meta name="color-scheme" content="light dark">
    
    <title>Pengaturan Voting - Pilkasis</title>
    
    <link rel="manifest" href="../../public/manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="../../public/icons/icon-192.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/mobile.css">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #11998e;
            --success-light: #38ef7d;
            --danger-color: #ee0979;
            --danger-light: #ff6a00;
            --dark-bg: #1a1a1a;
            --dark-card: #2a2a2a;
            --dark-text: #e0e0e0;
        }

        * {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(135deg, #0f0c29 0%, #302b63 100%);
                color: var(--dark-text);
            }
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

        /* ===== NAVBAR ===== */
        .navbar-custom {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
        }

        .navbar-custom .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            padding: 3px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        @media (prefers-color-scheme: dark) {
            .navbar-custom {
                background: linear-gradient(135deg, #5a3a7f 0%, #4a3fa0 100%);
            }
        }

        /* ===== PAGE TITLE ===== */
        .page-title {
            margin-top: 30px;
            margin-bottom: 30px;
            animation: fadeInUp 0.6s ease-out 0.3s both;
        }

        .page-title h2 {
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.2rem;
        }

        .page-title p {
            color: #666;
            font-size: 1.1rem;
        }

        @media (prefers-color-scheme: dark) {
            .page-title p {
                color: #aaa;
            }
        }

        /* ===== STATUS PREVIEW WIDGET ===== */
        .status-widget {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            border: 2px solid transparent;
            animation: slideInUp 0.5s ease-out 0.2s both;
        }

        .status-widget.status-open {
            border-color: var(--success-light);
            background: linear-gradient(135deg, rgba(17, 153, 142, 0.05) 0%, rgba(56, 239, 125, 0.05) 100%);
        }

        .status-widget.status-closed {
            border-color: var(--danger-light);
            background: linear-gradient(135deg, rgba(238, 9, 121, 0.05) 0%, rgba(255, 106, 0, 0.05) 100%);
        }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .widget-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .widget-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
        }

        @media (prefers-color-scheme: dark) {
            .status-widget {
                background: var(--dark-card);
                border-color: #444;
            }

            .widget-title {
                color: var(--dark-text);
            }
        }

        .widget-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .widget-item {
            padding: 15px;
            background: white;
            border-radius: 10px;
            text-align: center;
            border: 2px solid #f0f0f0;
        }

        @media (prefers-color-scheme: dark) {
            .widget-item {
                background: #1a1a1a;
                border-color: #333;
            }
        }

        .widget-label {
            font-size: 0.85rem;
            color: #666;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (prefers-color-scheme: dark) {
            .widget-label {
                color: #999;
            }
        }

        .widget-value {
            font-size: 1.4rem;
            font-weight: bold;
            color: #333;
        }

        @media (prefers-color-scheme: dark) {
            .widget-value {
                color: var(--dark-text);
            }
        }

        .countdown-text {
            font-size: 0.95rem;
            color: #666;
            margin-top: 8px;
        }

        /* ===== QUICK TOGGLE ===== */
        .quick-toggle {
            margin-bottom: 30px;
        }

        .quick-toggle-btn {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            background: white;
            font-weight: bold;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        @media (prefers-color-scheme: dark) {
            .quick-toggle-btn {
                background: var(--dark-card);
                border-color: #444;
                color: var(--dark-text);
            }
        }

        .quick-toggle-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .quick-toggle-btn.toggle-open {
            border-color: var(--success-light);
            color: var(--success-color);
        }

        .quick-toggle-btn.toggle-closed {
            border-color: var(--danger-light);
            color: var(--danger-color);
        }

        /* ===== CARDS ===== */
        .settings-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            background: white;
            margin-bottom: 30px;
            overflow: hidden;
            animation: slideInUp 0.5s ease-out 0.3s both;
        }

        @media (prefers-color-scheme: dark) {
            .settings-card {
                background: var(--dark-card);
            }
        }

        .settings-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px 15px 0 0;
        }

        .settings-header h4 {
            margin: 0;
            font-weight: bold;
            font-size: 1.3rem;
        }

        .settings-body {
            padding: 30px;
        }

        /* ===== FORM ELEMENTS ===== */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
            display: block;
            font-size: 1rem;
        }

        @media (prefers-color-scheme: dark) {
            .form-label {
                color: var(--dark-text);
            }
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            transition: all 0.3s;
            background: white;
            color: #333;
        }

        @media (prefers-color-scheme: dark) {
            .form-control {
                background: #1a1a1a;
                color: var(--dark-text);
                border-color: #444;
            }
        }

        .form-control:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.25);
            background: white;
        }

        @media (prefers-color-scheme: dark) {
            .form-control:focus {
                background: #2a2a2a;
            }
        }

        /* ===== RADIO GROUP ===== */
        .radio-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .radio-option {
            flex: 1;
            min-width: 200px;
        }

        .radio-option input[type="radio"] {
            display: none;
        }

        .radio-option label {
            display: block;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            margin: 0;
            background: white;
            color: #333;
        }

        @media (prefers-color-scheme: dark) {
            .radio-option label {
                background: #1a1a1a;
                border-color: #444;
                color: var(--dark-text);
            }
        }

        .radio-option input[type="radio"]:checked + label {
            border-color: #764ba2;
            background: linear-gradient(135deg, rgba(118, 75, 162, 0.1) 0%, rgba(102, 126, 234, 0.1) 100%);
            border-width: 2px;
            font-weight: 700;
        }

        /* ===== BUTTONS ===== */
        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 14px 40px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
            font-size: 1.05rem;
            cursor: pointer;
            min-height: 44px;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-back {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            min-height: 44px;
        }

        .btn-back:hover {
            background: #667eea;
            color: white;
        }

        /* ===== ALERTS & TOASTS ===== */
        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 15px 25px;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            animation: slideInRight 0.3s ease-out;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            max-width: 400px;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-notification.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .toast-notification.danger {
            background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
        }

        /* ===== INFO SECTION ===== */
        .info-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            border-left: 5px solid #667eea;
            margin-top: 30px;
            animation: slideInUp 0.5s ease-out 0.4s both;
        }

        @media (prefers-color-scheme: dark) {
            .info-section {
                background: var(--dark-card);
                border-left-color: #667eea;
            }
        }

        .info-section h5 {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .info-section ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .info-section li {
            margin-bottom: 10px;
            color: #666;
            line-height: 1.6;
        }

        @media (prefers-color-scheme: dark) {
            .info-section li {
                color: #999;
            }
        }

        /* ===== BUTTON GROUP ===== */
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        @media (max-width: 600px) {
            .button-group {
                flex-direction: column;
            }

            .loading-screen {
                display: flex;
            }

            .loading-icon {
                font-size: 60px;
            }

            .loading-title {
                font-size: 24px;
            }

            .loading-subtitle {
                font-size: 12px;
            }

            .page-title h2 {
                font-size: 1.8rem;
            }

            .widget-grid {
                grid-template-columns: 1fr;
            }

            .radio-group {
                flex-direction: column;
            }

            .radio-option {
                min-width: 100%;
            }
        }

        /* ===== CONFIRMATION DIALOG ===== */
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        @media (prefers-color-scheme: dark) {
            .modal-content {
                background: var(--dark-card);
                color: var(--dark-text);
            }

            .modal-header {
                border-bottom-color: #444;
            }
        }

        .modal-body {
            padding: 25px;
        }

        .confirmation-text {
            font-size: 1.1rem;
            margin: 15px 0;
            font-weight: 500;
        }

        .status-indicator {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
            vertical-align: middle;
        }

        .status-indicator.open {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .status-indicator.closed {
            background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-container">
            <div class="loading-icon">
                <img src="../../public/images/pramuka-logo.png" alt="Logo" class="loading-logo">
            </div>
            <div class="loading-title">PILKASIS</div>
            <div class="loading-subtitle">Pengaturan Voting</div>
            <div class="loading-bar-container">
                <div class="loading-bar"></div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container-lg">
            <a class="navbar-brand" href="dashboard.php" style="display: flex; align-items: center; gap: 10px;">
                <?php if (file_exists('../../public/images/pramuka-logo.png')): ?>
                    <img src="../../public/images/pramuka-logo.png" alt="Logo" class="navbar-logo">
                <?php endif; ?>
                <span><i class="bi bi-diagram-3-fill"></i> PILKASIS</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link text-white">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['nama']); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-light btn-sm ms-2" href="../../logout.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-lg">
        <!-- Page Title -->
        <div class="page-title">
            <h2><i class="bi bi-gear-fill"></i> Pengaturan Voting</h2>
            <p>Kelola status dan jadwal voting Pilkasis Anda</p>
        </div>

        <!-- Alert Messages -->
        <?php if ($message): ?>
            <div class="toast-notification <?php echo ($message_type === 'success' ? 'success' : 'danger'); ?>" id="toastNotif" role="alert">
                <i class="bi bi-<?php echo ($message_type === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill'); ?>"></i>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Status Preview Widget -->
        <?php if ($current_settings): ?>
            <div class="status-widget status-<?php echo $status; ?>">
                <div class="widget-header">
                    <div class="widget-title">
                        <span class="status-indicator <?php echo $status; ?>"></span>
                        Status Voting
                    </div>
                    <div>
                        <?php if ($status == 'open'): ?>
                            <span class="badge bg-success-light">
                                <i class="bi bi-play-circle-fill"></i> AKTIF
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger-light">
                                <i class="bi bi-stop-circle-fill"></i> DITUTUP
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="widget-grid">
                    <div class="widget-item">
                        <div class="widget-label">Status</div>
                        <div class="widget-value" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            <?php echo $status == 'open' ? 'Dibuka' : 'Ditutup'; ?>
                        </div>
                    </div>
                    
                    <?php if ($tanggal_mulai): ?>
                    <div class="widget-item">
                        <div class="widget-label">Mulai</div>
                        <div class="widget-value">
                            <?php echo date('d/m/Y', strtotime($tanggal_mulai)); ?>
                        </div>
                        <div class="countdown-text"><?php echo date('H:i', strtotime($tanggal_mulai)); ?></div>
                    </div>
                    <?php endif; ?>

                    <?php if ($tanggal_selesai): ?>
                    <div class="widget-item">
                        <div class="widget-label">Berakhir</div>
                        <div class="widget-value">
                            <?php echo date('d/m/Y', strtotime($tanggal_selesai)); ?>
                        </div>
                        <div class="countdown-text"><?php echo date('H:i', strtotime($tanggal_selesai)); ?></div>
                    </div>
                    <?php endif; ?>

                    <?php if ($countdown): ?>
                    <div class="widget-item">
                        <div class="widget-label">
                            <?php echo ($status == 'open' ? 'Sisa' : 'Status'); ?>
                        </div>
                        <div class="widget-value" style="font-size: 1.1rem; color: #667eea;">
                            <?php echo $countdown; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Quick Toggle Section -->
        <div class="quick-toggle">
            <div style="margin-bottom: 15px; color: #666;">
                <strong><i class="bi bi-lightning-charge-fill"></i> Akses Cepat</strong>
                <p style="font-size: 0.9rem; margin-top: 5px; color: #999;">Klik untuk mengubah status voting secara instant</p>
            </div>
            <form method="POST" id="quickToggleForm" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="submit" class="quick-toggle-btn toggle-open flex-grow-1" name="quick_toggle" value="open" id="btnToggleOpen">
                    <i class="bi bi-play-circle-fill"></i> Buka Voting Sekarang
                </button>
                <button type="submit" class="quick-toggle-btn toggle-closed flex-grow-1" name="quick_toggle" value="closed" id="btnToggleClosed">
                    <i class="bi bi-stop-circle-fill"></i> Tutup Voting Sekarang
                </button>
            </form>
        </div>

        <div class="settings-card">
            <div class="settings-header">
                <h4><i class="bi bi-sliders"></i> Kontrol Pemilihan</h4>
            </div>
            <div class="settings-body">
                <form method="POST" id="settingsForm">
                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-toggle-on"></i> Status Pemilihan</label>
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" id="status_open" name="status_pemilihan" value="open" 
                                    <?php echo ($status == 'open') ? 'checked' : ''; ?>>
                                <label for="status_open">
                                    <i class="bi bi-play-circle"></i> Buka Voting
                                </label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="status_closed" name="status_pemilihan" value="closed" 
                                    <?php echo ($status == 'closed') ? 'checked' : ''; ?>>
                                <label for="status_closed">
                                    <i class="bi bi-stop-circle"></i> Tutup Voting
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_mulai" class="form-label"><i class="bi bi-calendar-event"></i> Tanggal Mulai</label>
                        <input type="datetime-local" class="form-control" id="tanggal_mulai" name="tanggal_mulai" 
                            value="<?php echo $tanggal_mulai ? date('Y-m-d\TH:i', strtotime($tanggal_mulai)) : ''; ?>">
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle"></i> Opsional - Hanya untuk pencatatan
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_selesai" class="form-label"><i class="bi bi-calendar-event"></i> Tanggal Selesai</label>
                        <input type="datetime-local" class="form-control" id="tanggal_selesai" name="tanggal_selesai" 
                            value="<?php echo $tanggal_selesai ? date('Y-m-d\TH:i', strtotime($tanggal_selesai)) : ''; ?>">
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle"></i> Opsional - Sistem akan menghitung mundur ke waktu ini
                        </small>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn-save" id="btnSubmit">
                            <i class="bi bi-check-circle"></i> Simpan Pengaturan
                        </button>
                        <a href="dashboard.php" class="btn btn-back">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <h5><i class="bi bi-info-circle-fill"></i> Informasi Penting</h5>
            <ul>
                <li><strong>Status Voting:</strong> Mengontrol apakah siswa dan guru dapat melakukan voting</li>
                <li><strong>Buka:</strong> Semua pengguna dapat memilih kandidat</li>
                <li><strong>Tutup:</strong> Tidak ada yang dapat melakukan voting</li>
                <li><strong>Countdown:</strong> Sistem secara otomatis menghitung sisa waktu sampai deadline</li>
                <li><strong>Real-time:</strong> Perubahan langsung berlaku untuk semua pengguna aktif</li>
            </ul>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationLabel">
                        <i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Perubahan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="confirmation-text">
                        Anda akan mengubah status voting menjadi:
                    </p>
                    <div style="padding: 15px; background: #f9f9f9; border-radius: 10px; margin: 15px 0;">
                        <span class="status-indicator" id="confirmStatusIndicator"></span>
                        <strong id="confirmStatusText" style="font-size: 1.1rem;"></strong>
                    </div>
                    <p class="text-muted small" style="margin-top: 20px;">
                        <i class="bi bi-info-circle"></i> Perubahan ini akan langsung berlaku untuk semua pengguna.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmSave" 
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="bi bi-check-circle"></i> Ya, Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ===== LOADING SCREEN =====
        window.addEventListener('load', function() {
            const loadingScreen = document.getElementById('loadingScreen');
            setTimeout(() => {
                loadingScreen.classList.add('hidden');
            }, 2000);
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 2500);
        });

        // ===== FORM CONFIRMATION =====
        let confirmModal = null;
        let currentFormData = null;

        document.getElementById('btnSubmit').addEventListener('click', function(e) {
            e.preventDefault();
            const selectedStatus = document.querySelector('input[name="status_pemilihan"]:checked').value;
            const statusText = selectedStatus === 'open' ? 'Dibuka' : 'Ditutup';
            
            document.getElementById('confirmStatusText').textContent = statusText;
            document.getElementById('confirmStatusIndicator').className = 
                'status-indicator ' + selectedStatus;
            
            currentFormData = new FormData(document.getElementById('settingsForm'));
            
            if (!confirmModal) {
                confirmModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            }
            confirmModal.show();
        });

        document.getElementById('btnConfirmSave').addEventListener('click', function() {
            document.getElementById('settingsForm').submit();
        });

        // ===== QUICK TOGGLE =====
        document.getElementById('quickToggleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const toggleValue = e.submitter.value;
            
            // Set status directly from quick toggle
            document.querySelector('input[name="status_pemilihan"][value="' + toggleValue + '"]').checked = true;
            
            // Show confirmation
            const statusText = toggleValue === 'open' ? 'Dibuka' : 'Ditutup';
            document.getElementById('confirmStatusText').textContent = statusText;
            document.getElementById('confirmStatusIndicator').className = 
                'status-indicator ' + toggleValue;
            
            if (!confirmModal) {
                confirmModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            }
            confirmModal.show();
        });

        // ===== AUTO-HIDE TOAST =====
        const toast = document.getElementById('toastNotif');
        if (toast) {
            setTimeout(function() {
                toast.style.animation = 'slideInRight 0.3s ease-out reverse';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3500);
        }

        // ===== SERVICE WORKER & PWA =====
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('../../public/js/service-worker.js').catch(() => {});
        }
    </script>
</body>
</html>
