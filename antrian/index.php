<?php session_start();

// Jika user login, redirect ke dashboard masing-masing
if (isset($_SESSION['user'])) {
    header('Location: ' . ($_SESSION['role'] == 'admin' ? 'admin.php' : 'user.php'));
    exit;
}

// Public view
include 'koneksi.php';

// Ambil antrian saat ini
$query = "SELECT * FROM antrian WHERE status = 'menunggu' ORDER BY id ASC LIMIT 1";
$result = mysqli_query($conn, $query);
$current = mysqli_fetch_assoc($result);

// Hitung total antrian menunggu
$query_total = "SELECT COUNT(*) as total FROM antrian WHERE status = 'menunggu'";
$result_total = mysqli_query($conn, $query_total);
$total = mysqli_fetch_assoc($result_total)['total'];
?>
< !DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Aplikasi Antrian</title>
        <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/cyborg/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container"><a class="navbar-brand" href="#"><i class="bi bi-queue-fill me-2"></i>Sistem Antrian
                    Digital</a>
                <div class="navbar-nav ms-auto"><a class="nav-link" href="login.php">Login</a></div>
            </div>
        </nav>
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center mb-4">
                        <div class="card-header">
                            <h4><i class="bi bi-display me-2"></i>Antrian Saat Ini</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="display-1 text-warning">
                                <?php echo $current ? $current['nomor_antrian'] : '--';
                                ?>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center mb-4">
                        <div class="card-header">
                            <h4><i class="bi bi-graph-up me-2"></i>Statistik</h4>
                        </div>
                        <div class="card-body">
                            <h3 class="text-info">
                                <?php echo $total;
                                ?>
                            </h3>
                            <p>Menunggu</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center mb-4">
                        <div class="card-header">
                            <h4><i class="bi bi-gear me-2"></i>Aksi</h4>
                        </div>
                        <div class="card-body">
                            <form action="ambil_antrian.php" method="post" class="mb-2"><button type="submit"
                                    class="btn btn-success btn-lg w-100"><i class="bi bi-plus-circle me-2"></i>Ambil
                                    Antrian</button></form>
                            <form action="panggil_antrian.php" method="post"><button type="submit"
                                    class="btn btn-warning btn-lg w-100"><i class="bi bi-megaphone me-2"></i>Panggil
                                    Berikutnya</button></form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="bi bi-list-ul me-2"></i>Log Antrian</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nomor</th>
                                            <th>Status</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $query_all = "SELECT * FROM antrian ORDER BY id DESC LIMIT 10";
                                        $result_all = mysqli_query($conn, $query_all);
                                        $no = 1;

                                        while ($row = mysqli_fetch_assoc($result_all)) {
                                            $status_badge = '';
                                            if ($row['status'] == 'menunggu')
                                                $status_badge = 'badge bg-warning';
                                            elseif ($row['status'] == 'dipanggil')
                                                $status_badge = 'badge bg-success';
                                            elseif ($row['status'] == 'selesai')
                                                $status_badge = 'badge bg-secondary';
                                            echo "<tr><td>{$no}</td><td>{$row['nomor_antrian']}</td><td><span class='{$status_badge}'>{$row['status']}</span></td><td>{$row['waktu']}</td></tr>";
                                            $no++;
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="bg-dark text-center text-light py-3 mt-5">
            <div class="container">
                <p>&copy;
                    2026 Sistem Antrian Digital. Powered by Bootstrap.</p>
            </div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>