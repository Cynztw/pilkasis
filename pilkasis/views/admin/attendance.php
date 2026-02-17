<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../login.php');
    exit;
}

require_once '../../config/Database.php';

$db = new Database();
$conn = $db->connect();

// Ambil data voting dan partisipan
$query = "SELECT u.id, u.nama, u.kelas, u.role, 
          CASE WHEN v.id IS NOT NULL THEN 'Sudah Voting' ELSE 'Belum Voting' END as status,
          c.nama as kandidat_dipilih,
          v.vote_weight,
          v.created_at as waktu_voting
          FROM users u
          LEFT JOIN votes v ON u.id = v.user_id
          LEFT JOIN candidates c ON v.candidate_id = c.id
          WHERE u.role IN ('siswa', 'guru')
          ORDER BY u.role, u.kelas, u.nama";

$result = $conn->query($query);
$voting_data = $result->fetch_all(MYSQLI_ASSOC);

// Hitung statistik
$total_users = 0;
$total_voted = 0;
$total_belum = 0;

foreach ($voting_data as $data) {
    if ($data['status'] == 'Sudah Voting') {
        $total_voted++;
    } else {
        $total_belum++;
    }
}
$total_users = $total_voted + $total_belum;

// Proses export ke Excel
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="Absensi_Voting_Pilkasis_' . date('Y-m-d_H-i-s') . '.xls"');
    
    echo "<html><head><meta charset='UTF-8'></head><body>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr style='background-color: #4CAF50; color: white;'>";
    echo "<th>No</th>";
    echo "<th>Nama</th>";
    echo "<th>Kelas</th>";
    echo "<th>Role</th>";
    echo "<th>Status Voting</th>";
    echo "<th>Kandidat Dipilih</th>";
    echo "<th>Bobot Vote</th>";
    echo "<th>Waktu Voting</th>";
    echo "</tr>";
    
    $no = 1;
    foreach ($voting_data as $data) {
        $bg_color = $data['status'] == 'Sudah Voting' ? '#E8F5E9' : '#FFEBEE';
        echo "<tr style='background-color: $bg_color;'>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . htmlspecialchars($data['nama']) . "</td>";
        echo "<td>" . htmlspecialchars($data['kelas']) . "</td>";
        echo "<td>" . ucfirst($data['role']) . "</td>";
        echo "<td>" . $data['status'] . "</td>";
        echo "<td>" . ($data['kandidat_dipilih'] ? htmlspecialchars($data['kandidat_dipilih']) : '-') . "</td>";
        echo "<td>" . ($data['vote_weight'] ? $data['vote_weight'] : '-') . "</td>";
        echo "<td>" . ($data['waktu_voting'] ? date('d/m/Y H:i', strtotime($data['waktu_voting'])) : '-') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "</body></html>";
    exit;
}

// Proses export ke CSV
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="Absensi_Voting_Pilkasis_' . date('Y-m-d_H-i-s') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for UTF-8
    
    // Header
    fputcsv($output, array('No', 'Nama', 'Kelas', 'Role', 'Status Voting', 'Kandidat Dipilih', 'Bobot Vote', 'Waktu Voting'), ';');
    
    // Data
    $no = 1;
    foreach ($voting_data as $data) {
        fputcsv($output, array(
            $no++,
            $data['nama'],
            $data['kelas'],
            ucfirst($data['role']),
            $data['status'],
            $data['kandidat_dipilih'] ?? '-',
            $data['vote_weight'] ?? '-',
            $data['waktu_voting'] ? date('d/m/Y H:i', strtotime($data['waktu_voting'])) : '-'
        ), ';');
    }
    
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absensi Voting - Pilkasis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
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
        .page-title {
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .page-title h2 {
            font-weight: bold;
            color: #333;
        }
        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            padding: 20px;
        }
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }
        .stat-voted {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        .stat-belum {
            background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
            color: white;
        }
        .stat-total {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .export-buttons {
            margin-bottom: 30px;
        }
        .btn-export {
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s;
            margin-right: 10px;
        }
        .btn-excel {
            background: linear-gradient(135deg, #138D89 0%, #148A83 100%);
            color: white;
        }
        .btn-excel:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(19, 141, 137, 0.4);
            color: white;
        }
        .btn-csv {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-csv:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .table-custom {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .table-custom thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .table-custom thead th {
            padding: 18px 15px;
            font-weight: 600;
            border: none;
        }
        .table-custom tbody td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }
        .table-custom tbody tr:hover {
            background-color: #f9f9f9;
        }
        .badge-voted {
            background: #11998e;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-belum {
            background: #ee0979;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-guru {
            background: #f5a623;
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        .badge-siswa {
            background: #667eea;
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        .summary-text {
            color: #666;
            font-size: 0.95rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
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

    <div class="container">
        <div class="page-title">
            <h2><i class="bi bi-clipboard-check"></i> Absensi Voting</h2>
            <p class="text-muted">Pantau dan export data partisipasi voting siswa dan guru</p>
        </div>

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card stat-total">
                    <div class="stat-value"><?php echo $total_users; ?></div>
                    <div class="stat-label"><i class="bi bi-people"></i> Total Peserta</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card stat-voted">
                    <div class="stat-value"><?php echo $total_voted; ?></div>
                    <div class="stat-label"><i class="bi bi-check-circle"></i> Sudah Voting</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card stat-belum">
                    <div class="stat-value"><?php echo $total_belum; ?></div>
                    <div class="stat-label"><i class="bi bi-clock"></i> Belum Voting</div>
                </div>
            </div>
        </div>

        <!-- Export Buttons -->
        <div class="export-buttons">
            <a href="?export=excel" class="btn btn-export btn-excel">
                <i class="bi bi-file-earmark-excel"></i> Export ke Excel
            </a>
            <a href="?export=csv" class="btn btn-export btn-csv">
                <i class="bi bi-file-earmark-csv"></i> Export ke CSV
            </a>
        </div>

        <!-- Tabel Data -->
        <div class="table-custom">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Kandidat Dipilih</th>
                        <th>Bobot</th>
                        <th>Waktu Voting</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($voting_data as $data): 
                    ?>
                        <tr>
                            <td><strong><?php echo $no++; ?></strong></td>
                            <td><?php echo htmlspecialchars($data['nama']); ?></td>
                            <td><?php echo htmlspecialchars($data['kelas']); ?></td>
                            <td>
                                <?php if ($data['role'] == 'guru'): ?>
                                    <span class="badge-guru">Guru</span>
                                <?php else: ?>
                                    <span class="badge-siswa">Siswa</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($data['status'] == 'Sudah Voting'): ?>
                                    <span class="badge-voted"><i class="bi bi-check-circle"></i> <?php echo $data['status']; ?></span>
                                <?php else: ?>
                                    <span class="badge-belum"><i class="bi bi-clock"></i> <?php echo $data['status']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo $data['kandidat_dipilih'] ? htmlspecialchars($data['kandidat_dipilih']) : '<span class="text-muted">-</span>'; ?>
                            </td>
                            <td>
                                <?php echo $data['vote_weight'] ? '<strong>' . $data['vote_weight'] . '</strong>' : '<span class="text-muted">-</span>'; ?>
                            </td>
                            <td>
                                <?php echo $data['waktu_voting'] ? date('d/m/Y H:i', strtotime($data['waktu_voting'])) : '<span class="text-muted">-</span>'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Ringkasan -->
        <div class="summary-text">
            <strong>Ringkasan:</strong> Dari <?php echo $total_users; ?> total peserta, 
            <strong class="text-success"><?php echo $total_voted; ?> sudah voting</strong> (<?php echo $total_users > 0 ? round(($total_voted/$total_users)*100, 1) : 0; ?>%) 
            dan <strong class="text-danger"><?php echo $total_belum; ?> belum voting</strong>.
        </div>

        <div style="margin-top: 30px; margin-bottom: 30px;">
            <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
