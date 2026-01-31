<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'user') {
    header('Location: login.php');
    exit;
}

include 'koneksi.php';

// Ambil antrian
$message = '';
if (isset($_POST['ambil'])) {
    $query = "SELECT MAX(nomor_antrian) as max FROM antrian";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $next_number = ($row['max'] ? $row['max'] + 1 : 1);

    $waktu = date('Y-m-d H:i:s');
    $query_insert = "INSERT INTO antrian (nomor_antrian, status, waktu) VALUES ($next_number, 'menunggu', '$waktu')";
    if (mysqli_query($conn, $query_insert)) {
        $message = "Antrian Anda: <strong>$next_number</strong>";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Cek status antrian user (asumsikan berdasarkan session, tapi sederhana, tampilkan semua)
$query_user = "SELECT * FROM antrian WHERE status != 'selesai' ORDER BY id DESC LIMIT 5";
$result_user = mysqli_query($conn, $query_user);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard - Sistem Antrian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/cyborg/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-person-circle me-2"></i>User Dashboard</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center mb-4">
                    <div class="card-header">
                        <h4><i class="bi bi-ticket me-2"></i>Ambil Nomor Antrian</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                            <div class="alert alert-success"><?php echo $message; ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <button type="submit" name="ambil" class="btn btn-primary btn-lg"><i class="bi bi-plus-circle me-2"></i>Ambil Antrian</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4><i class="bi bi-clock me-2"></i>Status Antrian</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result_user)): ?>
                                        <tr>
                                            <td><?php echo $row['nomor_antrian']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $row['status'] == 'menunggu' ? 'warning' : 'success'; ?>">
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