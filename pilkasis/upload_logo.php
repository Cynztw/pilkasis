<?php
/**
 * Logo Upload Handler
 * Menangani upload logo Pramuka ke folder yang tepat
 */

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'Upload gagal'
];

try {
    // Validasi file upload
    if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File tidak ditemukan atau terjadi error');
    }

    $file = $_FILES['logo'];
    
    // Validasi tipe file
    $allowedTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/jpg'];
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Format file tidak didukung. Gunakan PNG, JPG, atau WebP');
    }

    // Validasi ukuran file (max 500KB)
    if ($file['size'] > 500 * 1024) {
        throw new Exception('Ukuran file terlalu besar (max 500KB)');
    }

    // Tentukan folder tujuan
    $targetDir = __DIR__ . '/public/images/';
    
    // Buat folder jika belum ada
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Tentukan nama file
    $filename = 'pramuka-logo.png';
    $targetPath = $targetDir . $filename;

    // Handle khusus untuk JPG/JPEG - convert ke PNG
    if ($file['type'] === 'image/jpeg' || $file['type'] === 'image/jpg') {
        // Load gambar
        $image = imagecreatefromjpeg($file['tmp_name']);
        if ($image === false) {
            throw new Exception('Gagal memproses gambar JPEG');
        }
        
        // Save sebagai PNG
        if (!imagepng($image, $targetPath)) {
            throw new Exception('Gagal menyimpan logo');
        }
        imagedestroy($image);
    } 
    // Format PNG - copy langsung
    else if ($file['type'] === 'image/png') {
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Gagal memindahkan file');
        }
    }
    // Format WebP - copy langsung
    else if ($file['type'] === 'image/webp') {
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Gagal memindahkan file');
        }
    }

    // Verify file exists dan cek ukuran
    if (!file_exists($targetPath)) {
        throw new Exception('File tidak tersimpan dengan benar');
    }

    $fileSize = filesize($targetPath);
    if ($fileSize === 0) {
        throw new Exception('File kosong');
    }

    // Success!
    $response = [
        'success' => true,
        'message' => 'Logo berhasil disimpan!',
        'file' => $filename,
        'size' => $fileSize,
        'path' => 'public/images/' . $filename
    ];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
