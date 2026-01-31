<?php
include 'koneksi.php';

// Panggil antrian pertama yang menunggu
$query = "UPDATE antrian SET status = 'dipanggil' WHERE status = 'menunggu' ORDER BY id ASC LIMIT 1";
mysqli_query($conn, $query);

// Redirect kembali ke index
header('Location: index.php');
?>