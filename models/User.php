<?php
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel {
    protected $table = 'users';

    public function findByUsername($username) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->executeQuery($query, [':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>