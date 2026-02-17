<?php
class Vote {
    private $conn;
    private $table = 'votes';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function vote($user_id, $candidate_id) {
        // Check if user already voted
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            return false; // User sudah voting
        }

        // Get user role to determine vote weight
        $query = "SELECT role FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $vote_weight = ($user['role'] == 'guru') ? 2 : 1;

        $query = "INSERT INTO " . $this->table . " (user_id, candidate_id, vote_weight) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $user_id, $candidate_id, $vote_weight);
        return $stmt->execute();
    }

    public function hasVoted($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function getUserVote($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getTotalVotes() {
        $query = "SELECT SUM(vote_weight) as total FROM " . $this->table;
        $result = $this->conn->query($query)->fetch_assoc();
        return $result['total'] ?? 0;
    }

    public function deleteVote($user_id) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
}
?>