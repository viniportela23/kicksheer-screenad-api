<?php

// esta pagina cuida apenas da validacao do token JWT

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/Response.php';

class BaseController {
    protected $db;
    protected $requestMethod;
    protected $userId;
    protected $userName;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    public function validateJWT() {

        $headers = getallheaders();

        if (!isset($headers['Authorization']) && isset($headers['Token-Authorization'])) {
            $headers['Authorization'] = $headers['Token-Authorization'];
        }

        if (!isset($headers['Authorization'])) {
            Response::unauthorized(["message" => "Token de acesso não fornecido"]);
            return false;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        
        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
            
            // Armazena informações do usuário
            $this->userId = $decoded->sub;
            $this->userName = $decoded->name ?? null;
            $this->nivel = $decoded->nivel ?? null;

            // Verifica se o token está perto de expirar (opcional)
            $timeToExpire = $decoded->exp - time();
            
        // Gera novo token se estiver perto de expirar
            $novoToken = $this->geraTokenJWT(
                $this->userId,
                $this->userName,
                $this->nivel,
                time() + strtotime(JWT_EXPIRE)
            );
            
            return [
                'token' => $novoToken,
                'id' => $this->userId,
                'username' => $this->userName,
                'nivel' => $this->nivel
            ];
            
        } catch (Exception $e) {
            Response::unauthorized(["message" => "Token inválido ou expirado"]);
            return false;
        }
    }

    public function geraTokenJWT($id_user, $username, $nivel, $expira) {
        // Gera token JWT
        $token = JWT::encode([
            "sub" => $id_user,
            "name" => $username,
            "iat" => time(),
            "exp" => $expira,
            "nivel" => $nivel
        ], JWT_SECRET, 'HS256');

        return $token;
    }
}