<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

include 'koneksi.php';

// Panggil antrian
$message = '';
if (isset($_POST['panggil'])) {
    $query = "UPDATE antrian SET status = 'dipanggil' WHERE status = 'menunggu' ORDER BY id ASC LIMIT 1";
    if (mysqli_query($conn, $query)) {
        $message = 'Antrian berhasil dipanggil.';
    } else {
        $message = 'Error: ' . mysqli_error($conn);
    }
    header('Location: admin.php');
    exit;
}

// Reset antrian (opsional)
if (isset($_POST['reset'])) {
    mysqli_query($conn, "UPDATE antrian SET status = 'selesai' WHERE status = 'dipanggil'");
}

// Ambil data
$query_current = "SELECT * FROM antrian WHERE status = 'menunggu' ORDER BY id ASC LIMIT 1";
$result_current = mysqli_query($conn, $query_current);
$current = mysqli_fetch_assoc($result_current);

$query_total = "SELECT COUNT(*) as total FROM antrian WHERE status = 'menunggu'";
$result_total = mysqli_query($conn, $query_total);
$total = mysqli_fetch_assoc($result_total)['total'];

$query_all = "SELECT * FROM antrian ORDER BY id DESC LIMIT 20";
$result_all = mysqli_query($conn, $query_all);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Sistem Antrian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/cyborg/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-shield-check me-2"></i>Admin Dashboard</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card text-center mb-4">
                    <div class="card-header">
                        <h4><i class="bi bi-display me-2"></i>Antrian Saat Ini</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="display-1 text-warning"><?php echo $current ? $current['nomor_antrian'] : '--'; ?></h1>
                        <p>Total Menunggu: <strong><?php echo $total; ?></strong></p>
                        <form method="post" class="mt-3">
                            <button type="submit" name="panggil" class="btn btn-success btn-lg"><i class="bi bi-megaphone me-2"></i>Panggil Antrian</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="bi bi-gear me-2"></i>Kontrol</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" class="mb-3">
                            <button type="submit" name="reset" class="btn btn-warning w-100"><i class="bi bi-arrow-repeat me-2"></i>Reset Dipanggil ke Selesai</button>
                        </form>
                        <a href="index.php" class="btn btn-info w-100"><i class="bi bi-eye me-2"></i>Lihat Public View</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="bi bi-list-ul me-2"></i>Semua Antrian</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nomor</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result_all)): ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['nomor_antrian']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $row['status'] == 'menunggu' ? 'warning' : ($row['status'] == 'dipanggil' ? 'success' : 'secondary'); ?>">
                                                    <?php echo $row['status']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $row['waktu']; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>