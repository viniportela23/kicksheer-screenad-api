<?php
class BaseModel {
    protected $db;
    protected $table;

    public function __construct($db) {
        $this->db = $db;
    }

    protected function executeQuery($query, $params = []) {
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Erro na execução da query: " . $e->getMessage());
        }
    }
}