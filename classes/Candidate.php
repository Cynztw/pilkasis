<?php
class Candidate {
    private $conn;
    private $table = 'candidates';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT c.*, COALESCE(SUM(v.vote_weight), 0) as vote_count FROM " . $this->table . " c 
                  LEFT JOIN votes v ON c.id = v.candidate_id 
                  GROUP BY c.id ORDER BY c.id";
        return $this->conn->query($query);
    }

    public function getById($id) {
        $query = "SELECT c.*, COALESCE(SUM(v.vote_weight), 0) as vote_count FROM " . $this->table . " c 
                  LEFT JOIN votes v ON c.id = v.candidate_id 
                  WHERE c.id = ? GROUP BY c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($nama, $kelas, $visi, $misi, $foto = null) {
        $query = "INSERT INTO " . $this->table . " (nama, kelas, visi, misi, foto) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss", $nama, $kelas, $visi, $misi, $foto);
        return $stmt->execute();
    }

    public function update($id, $nama, $kelas, $visi, $misi) {
        $query = "UPDATE " . $this->table . " SET nama = ?, kelas = ?, visi = ?, misi = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssi", $nama, $kelas, $visi, $misi, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getResults() {
        $query = "SELECT c.*, COALESCE(SUM(v.vote_weight), 0) as vote_count FROM " . $this->table . " c 
                  LEFT JOIN votes v ON c.id = v.candidate_id 
                  GROUP BY c.id ORDER BY vote_count DESC";
        return $this->conn->query($query);
    }
}
?>