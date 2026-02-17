<?php
require_once 'config/Database.php';
require_once 'classes/User.php';

$db = new Database();
$conn = $db->connect();
$user = new User($conn);

echo "=== Testing Admin Login ===\n";
$result = $user->login('admin', 'admin123');
if ($result) {
    echo "✓ Admin login BERHASIL\n";
    echo "  Username: " . $result['username'] . "\n";
    echo "  Nama: " . $result['nama'] . "\n";
    echo "  Role: " . $result['role'] . "\n";
} else {
    echo "✗ Admin login GAGAL\n";
}

echo "\n=== Testing Guru Login ===\n";
$result = $user->login('guru1', 'guru123');
if ($result) {
    echo "✓ Guru1 login BERHASIL\n";
    echo "  Username: " . $result['username'] . "\n";
    echo "  Nama: " . $result['nama'] . "\n";
    echo "  Role: " . $result['role'] . "\n";
} else {
    echo "✗ Guru1 login GAGAL\n";
}

echo "\n=== Database Check ===\n";
$query = "SELECT id, username, nama, role FROM users ORDER BY id";
$result = $conn->query($query);
echo "Total users: " . $result->num_rows . "\n";
while ($row = $result->fetch_assoc()) {
    echo "  - {$row['username']} ({$row['role']}): {$row['nama']}\n";
}

$conn->close();
?>
