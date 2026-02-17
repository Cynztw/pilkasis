<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../login.php');
    exit;
}

require_once '../../config/Database.php';
require_once '../../classes/Candidate.php';
require_once '../../classes/Vote.php';

$db = new Database();
$conn = $db->connect();
$candidate = new Candidate($conn);
$vote = new Vote($conn);

$candidates = $candidate->getResults();
$total_votes = $vote->getTotalVotes();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=yes">
    <meta name="description" content="Admin Dashboard - Pilkasis Voting System">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Pilkasis">
    <meta name="theme-color" content="#764ba2">
    <meta name="color-scheme" content="light dark">
    
    <title>Admin Dashboard - Pilkasis</title>
    
    <link rel="manifest" href="../../public/manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="../../public/icons/icon-192.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/mobile.css">
    
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
            background-color: #f8f9fa;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .navbar-custom .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
        }
        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s;
            margin-bottom: 20px;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .stat-card-votes {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-card-candidates {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 15px 0;
        }
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }
        .control-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            background: white;
            margin-bottom: 20px;
        }
        .control-card .card-header {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            border: none;
            padding: 20px;
        }
        .control-card .card-body {
            padding: 25px;
        }
        .btn-control {
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .btn-manage {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .btn-manage:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-settings {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
            border: none;
        }
        .btn-settings:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(250, 112, 154, 0.4);
            color: white;
        }
        .btn-attendance {
            background: linear-gradient(135deg, #138D89 0%, #148A83 100%);
            color: white;
            border: none;
        }
        .btn-attendance:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(19, 141, 137, 0.4);
            color: white;
        }
        .results-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            background: white;
            margin-top: 20px;
        }
        .results-card .card-header {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            border: none;
            padding: 20px;
        }
        .results-card .card-body {
            padding: 25px;
        }
        .candidate-row {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s;
        }
        .candidate-row:hover {
            background: #f9f9f9;
        }
        .candidate-name {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }
        .candidate-class {
            color: #666;
            font-size: 0.9rem;
        }
        .vote-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .vote-count {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .progress-custom {
            height: 25px;
            border-radius: 10px;
            background: #e0e0e0;
            overflow: hidden;
        }
        .progress-custom .progress-bar {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
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
            <div class="loading-subtitle">Dashboard Admin</div>
            <div class="loading-bar-container">
                <div class="loading-bar"></div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="../../index.php">
                <i class="bi bi-shield-check me-2"></i>Dashboard Admin
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-white"><small><i class="bi bi-person"></i> <?php echo htmlspecialchars($_SESSION['nama']); ?></small></span>
                <a class="btn btn-light btn-sm" href="../../logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-6">
                <div class="stat-card stat-card-votes">
                    <div class="p-4 text-center">
                        <i class="bi bi-hand-thumbs-up" style="font-size: 2.5rem;"></i>
                        <div class="stat-label mt-3">Total Voting</div>
                        <div class="stat-number"><?php echo $total_votes; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card stat-card-candidates">
                    <div class="p-4 text-center">
                        <i class="bi bi-person-badge" style="font-size: 2.5rem;"></i>
                        <div class="stat-label mt-3">Total Kandidat</div>
                        <div class="stat-number"><?php echo mysqli_num_rows($candidates); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Control Panel -->
        <div class="control-card">
            <div class="card-header">
                <h5><i class="bi bi-sliders"></i> Panel Kontrol</h5>
            </div>
            <div class="card-body">
                <a href="manage_candidates.php" class="btn btn-control btn-manage">
                    <i class="bi bi-person-plus"></i> Kelola Kandidat
                </a>
                <a href="settings.php" class="btn btn-control btn-settings">
                    <i class="bi bi-gear"></i> Pengaturan Voting
                </a>
                <a href="attendance.php" class="btn btn-control btn-attendance">
                    <i class="bi bi-clipboard-check"></i> Absensi Voting
                </a>
            </div>
        </div>

        <!-- Results Table -->
        <div class="results-card">
            <div class="card-header">
                <h5><i class="bi bi-bar-chart"></i> Hasil Perolehan Suara</h5>
            </div>
            <div class="card-body">
                <?php if (mysqli_num_rows($candidates) > 0): ?>
                    <?php 
                    $candidates = $candidate->getResults();
                    $index = 1;
                    while ($row = $candidates->fetch_assoc()): 
                        $persentase = $total_votes > 0 ? round(($row['vote_count'] / $total_votes) * 100, 2) : 0;
                    ?>
                        <div class="candidate-row">
                            <div class="flex-grow-1">
                                <div style="display: flex; gap: 40px; align-items: flex-start;">
                                    <div style="min-width: 200px;">
                                        <div class="candidate-name">
                                            <i class="bi bi-hash"></i> <?php echo $index++; ?> - <?php echo htmlspecialchars($row['nama']); ?>
                                        </div>
                                        <div class="candidate-class">Kelas: <?php echo htmlspecialchars($row['kelas']); ?></div>
                                    </div>
                                    <div style="flex: 1; min-width: 300px;">
                                        <div class="progress-custom">
                                            <div class="progress-bar" style="width: <?php echo $persentase; ?>%">
                                                <?php echo $persentase; ?>%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="vote-info">
                                        <span class="vote-count"><?php echo $row['vote_count']; ?> Poin</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info text-center py-4">
                        <i class="bi bi-info-circle"></i> Belum ada data kandidat. Silakan tambahkan kandidat terlebih dahulu.
                    </div>
                <?php endif; ?>
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
            navigator.serviceWorker.register('../../public/js/service-worker.js').catch(() => {});
        }
    </script>
</body>
</html>