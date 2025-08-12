<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../config/database.php'; // <- precisa disso

class BaseController {
    protected $db;
    protected $requestMethod;
    protected $userId;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    protected function validateJWT() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["message" => "Token de acesso não fornecido"]);
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        
        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
            $this->userId = $decoded->sub;
            return true;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["message" => "Token inválido ou expirado"]);
            exit;
        }
    }
}
?>