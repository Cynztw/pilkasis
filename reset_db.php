<?php
// Direct SQL execution without complex parsing
require_once 'config/Database.php';

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Step 1: Drop database
echo "Step 1: Dropping old database...\n";
$conn->query("DROP DATABASE IF EXISTS pilkasis_db");
echo "✓ Done\n\n";

// Step 2: Create database
echo "Step 2: Creating database...\n";
$conn->query("CREATE DATABASE pilkasis_db");
$conn->select_db("pilkasis_db");
echo "✓ Done\n\n";

// Step 3: Create tables
echo "Step 3: Creating tables...\n";

$conn->query("CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(10),
    role ENUM('admin', 'siswa', 'guru') DEFAULT 'siswa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
echo "  ✓ users table created\n";

$conn->query("CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(10),
    visi TEXT,
    misi TEXT,
    foto VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
echo "  ✓ candidates table created\n";

$conn->query("CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    candidate_id INT NOT NULL,
    vote_weight INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE,
    UNIQUE KEY unique_vote (user_id)
)");
echo "  ✓ votes table created\n";

$conn->query("CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_pemilihan ENUM('open', 'closed') DEFAULT 'open',
    tanggal_mulai DATETIME,
    tanggal_selesai DATETIME,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");
echo "  ✓ settings table created\n";

// Step 4: Insert data
echo "\nStep 4: Inserting data...\n";

// Settings
$conn->query("INSERT INTO settings (status_pemilihan, tanggal_mulai, tanggal_selesai) VALUES ('open', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY))");
echo "  ✓ Settings inserted\n";

// Admin user
$conn->query("INSERT INTO users (username, password, nama, kelas, role) VALUES ('admin', '\$2y\$10\$if1v1.su/kFX9NZlMdgCVOxbPgv5d.CRq.sanaSnJCozBM99y9W6a', 'Admin OSIS', 'XII', 'admin')");
echo "  ✓ Admin user inserted\n";

// Guru users
$conn->query("INSERT INTO users (username, password, nama, kelas, role) VALUES ('guru1', '\$2y\$10\$y2Y7BHs2Rucy8AM1dJ47keXKrzzsPh6MavWQO56R0CXBhynXGv.d', 'Ibu Ratna', 'Guru', 'guru')");
echo "  ✓ Guru1 inserted\n";

$conn->query("INSERT INTO users (username, password, nama, kelas, role) VALUES ('guru2', '\$2y\$10\$y2Y7BHs2Rucy8AM1dJ47keXKrzzsPh6MavWQO56R0CXBhynXGv.d', 'Bapak Hendra', 'Guru', 'guru')");
echo "  ✓ Guru2 inserted\n";

// Sample candidates
$conn->query("INSERT INTO candidates (nama, kelas, visi, misi) VALUES ('Budi Santoso', 'XII-A', 'Membawa OSIS ke level yang lebih baik', 'Meningkatkan kegiatan ekstrakurikuler')");
echo "  ✓ Candidate 1 inserted\n";

$conn->query("INSERT INTO candidates (nama, kelas, visi, misi) VALUES ('Siti Nurhaliza', 'XII-B', 'OSIS yang inklusif dan progresif', 'Dengarkan setiap suara siswa')");
echo "  ✓ Candidate 2 inserted\n";

$conn->query("INSERT INTO candidates (nama, kelas, visi, misi) VALUES ('Ahmad Hidayat', 'XII-C', 'Bersama kita bisa lebih baik', 'Transparansi dalam setiap keputusan')");
echo "  ✓ Candidate 3 inserted\n";

// Verify
echo "\nStep 5: Verifying...\n";
$users = $conn->query("SELECT COUNT(*) as count FROM users");
$row = $users->fetch_assoc();
echo "  Users in database: " . $row['count'] . "\n";

$candidates = $conn->query("SELECT COUNT(*) as count FROM candidates");
$row = $candidates->fetch_assoc();
echo "  Candidates in database: " . $row['count'] . "\n";

echo "\n" . str_repeat("=", 50) . "\n";
echo "✓ Database setup complete!\n";
echo str_repeat("=", 50) . "\n\n";

echo "=== Akun yang tersedia ===\n";
echo "ADMIN:\n";
echo "  Username: admin\n";
echo "  Password: admin123\n\n";
echo "GURU (setiap vote = 2 poin):\n";
echo "  Username: guru1 | Password: guru123 | Nama: Ibu Ratna\n";
echo "  Username: guru2 | Password: guru123 | Nama: Bapak Hendra\n\n";
echo "SISWA (setiap vote = 1 poin):\n";
echo "  Daftar melalui halaman register\n";

$conn->close();
?>
