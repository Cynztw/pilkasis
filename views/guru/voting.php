<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guru') {
    header('Location: ../../login.php');
    exit;
}

require_once '../../config/Database.php';
require_once '../../classes/Candidate.php';
require_once '../../classes/Vote.php';
require_once '../../classes/Settings.php';

$db = new Database();
$conn = $db->connect();
$candidate = new Candidate($conn);
$vote = new Vote($conn);
$settings = new Settings($conn);

$message = '';
$candidates = $candidate->getAll();

// Cek apakah user sudah voting
$has_voted = $vote->hasVoted($_SESSION['user_id']);

// Proses voting
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote'])) {
    if (!$settings->isPemilihanOpen()) {
        $message = 'Pemilihan sudah ditutup!';
    } elseif ($has_voted) {
        $message = 'Anda sudah voting sebelumnya!';
    } else {
        $candidate_id = $_POST['candidate_id'];
        if ($vote->vote($_SESSION['user_id'], $candidate_id)) {
            $message = 'Vote berhasil disimpan! (Guru: 2 poin)';
            $has_voted = true;
        } else {
            $message = 'Error saat menyimpan vote!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Voting - Pilkasis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-top: 60px;
        }
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        .navbar-custom .navbar-brand {
            color: #764ba2 !important;
            font-weight: bold;
            font-size: 1.3rem;
        }
        .voting-header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }
        .voting-header h2 {
            font-weight: bold;
            font-size: 2rem;
        }
        .voting-header p {
            font-size: 1.1rem;
            opacity: 0.95;
        }
        .candidate-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            overflow: hidden;
            transition: all 0.3s;
            height: 100%;
            background: white;
        }
        .candidate-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }
        .candidate-photo {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background: linear-gradient(135deg, #f5a623 0%, #f77f00 100%);
        }
        .candidate-photo-placeholder {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #f5a623 0%, #f77f00 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
        }
        .candidate-header {
            background: linear-gradient(135deg, #f5a623 0%, #f77f00 100%);
            color: white;
            padding: 25px 20px;
            text-align: center;
        }
        .candidate-name {
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .candidate-class {
            font-size: 0.95rem;
            opacity: 0.9;
        }
        .candidate-body {
            padding: 25px;
        }
        .vision-mision {
            margin-bottom: 20px;
        }
        .vision-title, .mision-title {
            font-weight: 600;
            color: #f5a623;
            font-size: 0.95rem;
            margin-bottom: 8px;
        }
        .vision-content, .mision-content {
            color: #555;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        .btn-vote {
            background: linear-gradient(135deg, #f5a623 0%, #f77f00 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-vote:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(245, 166, 35, 0.4);
            color: white;
        }
        .alert-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .alert-success-custom {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        .alert-danger-custom {
            background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
            color: white;
        }
        .alert-custom h4 {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .alert-custom p {
            margin-bottom: 0;
            font-size: 1rem;
        }
        .container {
            max-width: 1200px;
        }
        .point-badge {
            background: rgba(255, 255, 255, 0.9);
            color: #f5a623;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .guru-badge {
            background: linear-gradient(135deg, #f5a623 0%, #f77f00 100%);
            color: white;
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light navbar-custom sticky-top">
        <div class="container">
            <span class="navbar-brand"><i class="bi bi-person-check"></i> Sistem Voting OSIS</span>
            <div class="d-flex align-items-center gap-3">
                <span class="guru-badge"><i class="bi bi-mortarboard"></i> Guru</span>
                <span class="text-dark"><small><i class="bi bi-person"></i> <?php echo htmlspecialchars($_SESSION['nama']); ?></small></span>
                <a class="btn btn-sm btn-outline-danger" href="../../logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <?php if ($message): ?>
            <?php if (strpos($message, 'berhasil') !== false): ?>
                <div class="alert alert-custom alert-success-custom" role="alert">
                    <h4><i class="bi bi-check-circle-fill"></i> Terima Kasih!</h4>
                    <p><?php echo $message; ?> Suara Anda memiliki bobot 2 poin dan sangat berarti untuk pemilihan ketua OSIS.</p>
                </div>
            <?php else: ?>
                <div class="alert alert-custom alert-danger-custom" role="alert">
                    <h4><i class="bi bi-exclamation-circle-fill"></i> Pemberitahuan</h4>
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!$has_voted): ?>
            <div class="voting-header">
                <h2><i class="bi bi-hand-thumbs-up"></i> Pilih Calon Ketua OSIS</h2>
                <p>Pilih satu kandidat yang menurut Anda terbaik untuk memimpin OSIS</p>
                <div class="point-badge">
                    <i class="bi bi-star-fill"></i> Suara Anda: 2 Poin (Guru)
                </div>
            </div>
            <div class="row">
                <?php while ($row = $candidates->fetch_assoc()): ?>
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="candidate-card">
                            <?php if ($row['foto'] && file_exists('../../' . $row['foto'])): ?>
                                <img src="../../<?php echo htmlspecialchars($row['foto']); ?>" alt="<?php echo htmlspecialchars($row['nama']); ?>" class="candidate-photo">
                            <?php else: ?>
                                <div class="candidate-photo-placeholder">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                            <?php endif; ?>
                            <div class="candidate-header">
                                <div class="candidate-name"><?php echo htmlspecialchars($row['nama']); ?></div>
                                <div class="candidate-class"><i class="bi bi-bookmark"></i> Kelas <?php echo htmlspecialchars($row['kelas']); ?></div>
                            </div>
                            <div class="candidate-body">
                                <div class="vision-mision">
                                    <div class="vision-title"><i class="bi bi-lightbulb"></i> Visi</div>
                                    <div class="vision-content"><?php echo htmlspecialchars($row['visi']); ?></div>
                                </div>
                                <div class="vision-mision">
                                    <div class="mision-title"><i class="bi bi-target"></i> Misi</div>
                                    <div class="mision-content"><?php echo htmlspecialchars($row['misi']); ?></div>
                                </div>
                                <form method="post" style="display:inline; width: 100%;">
                                    <input type="hidden" name="candidate_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="vote" class="btn-vote">
                                        <i class="bi bi-check-circle"></i> Pilih Kandidat Ini
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px;">
                <div style="background: white; border-radius: 15px; padding: 50px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); max-width: 500px; margin: 0 auto;">
                    <i class="bi bi-check-circle-fill" style="font-size: 4rem; color: #f5a623; margin-bottom: 20px;"></i>
                    <h3 style="color: #333; margin-bottom: 15px;">Terima Kasih!</h3>
                    <p style="color: #666; font-size: 1.1rem;">Suara Anda telah dicatat dengan bobot <strong>2 poin</strong>. Apresiasi kami yang dalam atas partisipasi Anda dalam pemilihan ketua OSIS.</p>
                    <a href="../../index.php" class="btn btn-outline-primary mt-3">
                        <i class="bi bi-house"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
