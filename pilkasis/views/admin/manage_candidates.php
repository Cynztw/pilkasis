<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../login.php');
    exit;
}

require_once '../../config/Database.php';
require_once '../../classes/Candidate.php';

$db = new Database();
$conn = $db->connect();
$candidate = new Candidate($conn);

$message = '';
$candidates = $candidate->getAll();

// Proses tambah kandidat
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $nama = $_POST['nama'];
        $kelas = $_POST['kelas'];
        $visi = $_POST['visi'];
        $misi = $_POST['misi'];
        $foto = null;
        
        // Proses upload foto
        if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
            $target_dir = "../../public/images/candidates/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
            
            if (in_array($file_ext, $allowed_ext)) {
                $filename = uniqid() . '.' . $file_ext;
                $target_file = $target_dir . $filename;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                    $foto = 'public/images/candidates/' . $filename;
                } else {
                    $message = 'Gagal upload foto!';
                }
            } else {
                $message = 'Format file tidak didukung (jpg, jpeg, png, gif)!';
            }
        }
        
        if (!isset($message)) {
            $query = "INSERT INTO candidates (nama, kelas, visi, misi, foto) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $nama, $kelas, $visi, $misi, $foto);
            
            if ($stmt->execute()) {
                $message = 'Kandidat berhasil ditambahkan!';
                $candidates = $candidate->getAll();
            } else {
                $message = 'Gagal menambahkan kandidat!';
            }
        }
    } elseif ($_POST['action'] == 'delete') {
        $id = $_POST['candidate_id'];
        $query = "DELETE FROM candidates WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $message = 'Kandidat berhasil dihapus!';
            $candidates = $candidate->getAll();
        } else {
            $message = 'Gagal menghapus kandidat!';
        }
    } elseif ($_POST['action'] == 'edit') {
        $id = $_POST['candidate_id'];
        $nama = $_POST['nama'];
        $kelas = $_POST['kelas'];
        $visi = $_POST['visi'];
        $misi = $_POST['misi'];
        
        // Ambil foto saat ini
        $query = "SELECT foto FROM candidates WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $current_row = $result->fetch_assoc();
        $foto = $current_row['foto'];
        
        // Proses upload foto baru
        if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
            $target_dir = "../../public/images/candidates/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
            
            if (in_array($file_ext, $allowed_ext)) {
                $filename = uniqid() . '.' . $file_ext;
                $target_file = $target_dir . $filename;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                    // Hapus foto lama jika ada
                    if ($foto && file_exists($foto)) {
                        unlink($foto);
                    }
                    $foto = 'public/images/candidates/' . $filename;
                } else {
                    $message = 'Gagal upload foto!';
                }
            } else {
                $message = 'Format file tidak didukung (jpg, jpeg, png, gif)!';
            }
        }
        
        if (!isset($message)) {
            $query = "UPDATE candidates SET nama = ?, kelas = ?, visi = ?, misi = ?, foto = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssi", $nama, $kelas, $visi, $misi, $foto, $id);
            
            if ($stmt->execute()) {
                $message = 'Kandidat berhasil diperbarui!';
                $candidates = $candidate->getAll();
            } else {
                $message = 'Gagal memperbarui kandidat!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Kandidat - Pilkasis</title>
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
        .btn-add-candidate {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s;
            margin-bottom: 30px;
        }
        .btn-add-candidate:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .candidate-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            background: white;
            margin-bottom: 20px;
            transition: all 0.3s;
            overflow: hidden;
        }
        .candidate-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .candidate-photo {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .candidate-photo-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 20px;
            border-radius: 15px 15px 0 0;
        }
        .card-header-custom h5 {
            margin: 0;
            font-weight: bold;
        }
        .card-body-custom {
            padding: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #667eea;
            min-width: 100px;
        }
        .info-value {
            color: #555;
            flex: 1;
            text-align: right;
        }
        .btn-action {
            padding: 8px 15px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            margin-right: 8px;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        .btn-edit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-delete {
            background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
            color: white;
        }
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4);
            color: white;
        }
        .action-buttons {
            text-align: right;
            margin-top: 15px;
        }
        .modal-content {
            border: none;
            border-radius: 15px;
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 15px 15px 0 0;
        }
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        .form-control, .form-textarea {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        .form-control:focus, .form-textarea:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.25);
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            width: 100%;
        }
        .btn-submit:hover {
            color: white;
            opacity: 0.95;
        }
        .alert-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .alert-success-custom {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        .alert-danger-custom {
            background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        .empty-state h4 {
            color: #999;
            margin-bottom: 10px;
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
            <h2><i class="bi bi-person-badge"></i> Kelola Kandidat</h2>
            <p class="text-muted">Tambah, edit, atau hapus kandidat Ketua OSIS</p>
        </div>

        <?php if ($message): ?>
            <?php if (strpos($message, 'berhasil') !== false): ?>
                <div class="alert alert-custom alert-success-custom" role="alert">
                    <i class="bi bi-check-circle"></i> <?php echo $message; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-custom alert-danger-custom" role="alert">
                    <i class="bi bi-exclamation-circle"></i> <?php echo $message; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <button class="btn btn-add-candidate" data-bs-toggle="modal" data-bs-target="#tambahKandidatModal">
            <i class="bi bi-plus-circle"></i> Tambah Kandidat
        </button>

        <?php if (mysqli_num_rows($candidates) > 0): ?>
            <div class="row">
                <?php 
                $candidates = $candidate->getAll();
                while ($row = $candidates->fetch_assoc()): 
                ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="candidate-card">
                            <?php if ($row['foto'] && file_exists($row['foto'])): ?>
                                <img src="../../<?php echo $row['foto']; ?>" alt="<?php echo htmlspecialchars($row['nama']); ?>" class="candidate-photo">
                            <?php else: ?>
                                <div class="candidate-photo-placeholder">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-header-custom">
                                <h5><?php echo htmlspecialchars($row['nama']); ?></h5>
                            </div>
                            <div class="card-body-custom">
                                <div class="info-row">
                                    <span class="info-label"><i class="bi bi-bookmark"></i> Kelas:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($row['kelas']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label"><i class="bi bi-lightbulb"></i> Visi:</span>
                                    <span class="info-value"><?php echo htmlspecialchars(substr($row['visi'], 0, 40)) . (strlen($row['visi']) > 40 ? '...' : ''); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label"><i class="bi bi-target"></i> Misi:</span>
                                    <span class="info-value"><?php echo htmlspecialchars(substr($row['misi'], 0, 40)) . (strlen($row['misi']) > 40 ? '...' : ''); ?></span>
                                </div>
                                <div class="action-buttons">
                                    <button class="btn btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#editKandidatModal" 
                                        onclick="editKandidat(<?php echo $row['id']; ?>, '<?php echo addslashes($row['nama']); ?>', '<?php echo addslashes($row['kelas']); ?>', '<?php echo addslashes($row['visi']); ?>', '<?php echo addslashes($row['misi']); ?>')">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-action btn-delete" onclick="deleteKandidat(<?php echo $row['id']; ?>)">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>Belum ada kandidat</h4>
                <p class="text-muted">Mulai dengan menambahkan kandidat pertama Anda</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal Tambah Kandidat -->
    <div class="modal fade" id="tambahKandidatModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Tambah Kandidat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="kelas" name="kelas" placeholder="Contoh: XII-A" required>
                        </div>
                        <div class="mb-3">
                            <label for="visi" class="form-label">Visi</label>
                            <textarea class="form-control form-textarea" id="visi" name="visi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="misi" class="form-label">Misi</label>
                            <textarea class="form-control form-textarea" id="misi" name="misi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Kandidat</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            <small class="text-muted">Format: JPG, JPEG, PNG, GIF (Optional)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="action" value="add" class="btn-submit">
                            <i class="bi bi-check-circle"></i> Tambah Kandidat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kandidat -->
    <div class="modal fade" id="editKandidatModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil"></i> Edit Kandidat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="candidate_id" id="edit_candidate_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_kelas" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="edit_kelas" name="kelas" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_visi" class="form-label">Visi</label>
                            <textarea class="form-control form-textarea" id="edit_visi" name="visi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_misi" class="form-label">Misi</label>
                            <textarea class="form-control form-textarea" id="edit_misi" name="misi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_foto" class="form-label">Foto Kandidat</label>
                            <input type="file" class="form-control" id="edit_foto" name="foto" accept="image/*">
                            <small class="text-muted">Format: JPG, JPEG, PNG, GIF (Optional)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="action" value="edit" class="btn-submit">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Kandidat -->
    <div class="modal fade" id="deleteKandidatModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kandidat ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <form method="POST">
                    <input type="hidden" name="candidate_id" id="delete_candidate_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="action" value="delete" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editKandidat(id, nama, kelas, visi, misi) {
            document.getElementById('edit_candidate_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_kelas').value = kelas;
            document.getElementById('edit_visi').value = visi;
            document.getElementById('edit_misi').value = misi;
        }

        function deleteKandidat(id) {
            document.getElementById('delete_candidate_id').value = id;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteKandidatModal'));
            deleteModal.show();
        }
    </script>
</body>
</html>
