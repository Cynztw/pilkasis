<?php
/**
 * Template Halaman dengan PWA Support
 * 
 * Gunakan template ini sebagai referensi untuk update halaman-halaman lain:
 * - login.php
 * - register.php
 * - views/admin/dashboard.php
 * - views/admin/manage_candidates.php
 * - views/admin/settings.php
 * - views/admin/attendance.php
 * - views/siswa/voting.php
 * - views/guru/voting.php
 * - Dll...
 */
?>

<!-- SETIAP HALAMAN HARUS MEMILIKI STRUKTUR INI -->

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- STEP 1: Charset & Mobile-Head (WAJIB) -->
    <meta charset="UTF-8">
    <?php require_once '../../includes/mobile-head.php'; ?>
    <!-- Catatan: Sesuaikan path ../ atau ../../ berdasarkan lokasi file -->
    
    <!-- STEP 2: Title -->
    <title>Nama Halaman - Pilkasis</title>
    
    <!-- STEP 3: Bootstrap & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- STEP 4: Mobile CSS (Opsional tapi Recommended) -->
    <link rel="stylesheet" href="../../public/css/mobile.css">
    
    <!-- STEP 5: Inline Styles Untuk Halaman (Opsional) -->
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        /* Tambah styling tambahan di sini */
    </style>
</head>

<body>
    <!-- Navbar (Optional) -->
    <nav class="navbar navbar-light navbar-custom sticky-top">
        <div class="container">
            <span class="navbar-brand"><i class="bi bi-check2-square"></i> Pilkasis</span>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <!-- Content halaman di sini -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STEP 6: Bootstrap JS Bundle (Wajib) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- STEP 7: App JS untuk PWA Support (WAJIB) -->
    <script src="../../public/js/app.js"></script>
    <!-- Catatan: Sesuaikan path ../../ berdasarkan lokasi file -->
    
    <!-- STEP 8: Inline Scripts (Opsional) -->
    <script>
        // Script tambahan halaman di sini
    </script>
</body>
</html>

<!-- ===================================================
CONTOH IMPLEMENTASI UNTUK BERBAGAI HALAMAN
===================================================== -->

<!-- 1. UNTUK login.php (di root folder) -->
<?php
/*
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <?php require_once 'includes/mobile-head.php'; ?>
    
    <title>Login - Pilkasis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/css/mobile.css">
    
    <style>
        body { background: linear-gradient(...); }
    </style>
</head>
<body>
    <!-- Navbar -->
    <!-- Form Login -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/app.js"></script>
</body>
</html>
*/
?>

<!-- 2. UNTUK register.php (di root folder) -->
<?php
/*
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <?php require_once 'includes/mobile-head.php'; ?>
    
    <title>Register - Pilkasis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/css/mobile.css">
</head>
<body>
    <!-- Form Register -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/app.js"></script>
</body>
</html>
*/
?>

<!-- 3. UNTUK views/admin/dashboard.php (di subfolder) -->
<?php
/*
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <?php require_once '../../includes/mobile-head.php'; ?>
    
    <title>Dashboard Admin - Pilkasis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/mobile.css">
</head>
<body>
    <!-- Navbar -->
    <!-- Dashboard Content -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/js/app.js"></script>
</body>
</html>
*/
?>

<!-- 4. UNTUK views/siswa/voting.php -->
<?php
/*
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <?php require_once '../../includes/mobile-head.php'; ?>
    
    <title>Voting - Pilkasis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/mobile.css">
</head>
<body>
    <!-- Navbar -->
    <!-- Voting Interface -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/js/app.js"></script>
</body>
</html>
*/
?>

<!-- ===================================================
PATH REFERENCE
===================================================== -->
<?php
/*
Untuk menentukan path yang benar, perhatikan lokasi file:

ROOT FOLDER (login.php, index.php, register.php):
- includes/mobile-head.php → gunakan: 'includes/mobile-head.php'
- public/css/mobile.css     → gunakan: 'public/css/mobile.css'
- public/js/app.js          → gunakan: 'public/js/app.js'

SUBFOLDER 1 LEVEL (views/admin/dashboard.php, views/siswa/voting.php):
- includes/mobile-head.php → gunakan: '../../includes/mobile-head.php'
- public/css/mobile.css     → gunakan: '../../public/css/mobile.css'
- public/js/app.js          → gunakan: '../../public/js/app.js'

SUBFOLDER 2+ LEVEL (jika ada):
- Sesuaikan jumlah ../ dengan jumlah folder

Cara cepat menghitung:
1. Hitung berapa banyak folder dari file ke root
2. Setiap folder = satu ../
3. Contoh: views/admin/dashboard.php → 2 folder dari root = ../../
*/
?>

<!-- ===================================================
CHECKLIST SETIAP FILE
===================================================== -->
<?php
/*
UNTUK SETIAP HALAMAN, PASTIKAN SUDAH MEMILIKI:

☐ <meta charset="UTF-8">
☐ <?php require_once '..../includes/mobile-head.php'; ?> (dengan path yang benar)
☐ <title>Nama Halaman - Pilkasis</title>
☐ Bootstrap CSS: https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css
☐ Bootstrap Icons: https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css
☐ Mobile CSS: href="..../public/css/mobile.css"
☐ Bootstrap JS Bundle (sebelum </body>): https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js
☐ App JS (sebelum </body>): src="..../public/js/app.js"

Jika sudah semua ✓ → Halaman siap untuk PWA
*/
?>
