<?php
include 'koneksi.php';

// Ambil nomor antrian terakhir
$query = "SELECT MAX(nomor_antrian) as max FROM antrian";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$next_number = ($row['max'] ? $row['max'] + 1 : 1);

// Insert antrian baru
$waktu = date('Y-m-d H:i:s');
$query_insert = "INSERT INTO antrian (nomor_antrian, status, waktu) VALUES ($next_number, 'menunggu', '$waktu')";
mysqli_query($conn, $query_insert);

// Redirect kembali ke index
header('Location: index.php');
?>