<?php

// inicia a conexão com o banco de dados

class BaseModel {
    protected $db;
    protected $table;

    public function __construct($db) {
        $this->db = $db;
        
        // Configuração para retornar tipos nativos
        $this->db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
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