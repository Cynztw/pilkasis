<?php
class Settings {
    private $conn;
    private $table = 'settings';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getSettings() {
        $query = "SELECT * FROM " . $this->table . " LIMIT 1";
        return $this->conn->query($query)->fetch_assoc();
    }

    public function updateStatus($status) {
        $query = "UPDATE " . $this->table . " SET status_pemilihan = ? WHERE id = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $status);
        return $stmt->execute();
    }

    public function updateDates($tanggal_mulai, $tanggal_selesai) {
        $query = "UPDATE " . $this->table . " SET tanggal_mulai = ?, tanggal_selesai = ? WHERE id = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $tanggal_mulai, $tanggal_selesai);
        return $stmt->execute();
    }

    public function isPemilihanOpen() {
        $settings = $this->getSettings();
        return $settings['status_pemilihan'] == 'open';
    }
}
?>