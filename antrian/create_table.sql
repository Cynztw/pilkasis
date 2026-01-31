CREATE DATABASE IF NOT EXISTS antrian_db;

USE antrian_db;

CREATE TABLE IF NOT EXISTS antrian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_antrian INT NOT NULL,
    status ENUM('menunggu', 'dipanggil', 'selesai') DEFAULT 'menunggu',
    waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);