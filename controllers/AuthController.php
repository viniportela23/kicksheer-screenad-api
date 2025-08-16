<?php

// esta parte cuida da logica do login e criacao do token

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Busca.php';
require_once __DIR__ . '/../utils/Response.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key; // Adicione isso aqui

class AuthController extends BaseController {
    public function login() {
        if ($this->requestMethod !== 'POST') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        // Validação dos dados
        if (empty($data['username']) || empty($data['password'])) {
            Response::unprocessableEntity(["message" => "Usuário e senha são obrigatórios"]);
            return;
        }

        $userModel = new Buscas($this->db);
        $user = $userModel->findByUsername($data['username']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            Response::unauthorized(["message" => "Credenciais inválidas"]);
            return;
        }
        $tokenModel = new BaseController();
        $token = $tokenModel->geraTokenJWT($user['id'], $user['username'], $user['nivel'], strtotime(JWT_EXPIRE));


        Response::ok([
            "message" => "Login bem-sucedido",
            "token" => $token
        ]);
    }
}
?>