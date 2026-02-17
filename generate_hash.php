<?php
// Ini script untuk generate password hash yang tepat
$password = "guru123";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n\n";

// Test verifikasi
if (password_verify($password, $hash)) {
    echo "✓ Hash valid untuk password 'guru123'\n";
} else {
    echo "✗ Hash tidak valid\n";
}

// Juga untuk admin password
echo "\n--- Admin Password ---\n";
$admin_password = "admin123";
$admin_hash = password_hash($admin_password, PASSWORD_DEFAULT);
echo "Password: " . $admin_password . "\n";
echo "Hash: " . $admin_hash . "\n";

if (password_verify($admin_password, $admin_hash)) {
    echo "✓ Hash valid untuk password 'admin123'\n";
} else {
    echo "✗ Hash tidak valid\n";
}
?>
