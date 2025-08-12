<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/JWT.php';
require_once __DIR__ . '/../utils/Response.php';

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

        $userModel = new User($this->db);
        $user = $userModel->findByUsername($data['username']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            Response::unauthorized(["message" => "Credenciais inválidas"]);
            return;
        }

        // Gera token JWT
        $token = JWT::encode([
            "sub" => $user['id'],
            "name" => $user['username'],
            "iat" => time(),
            "exp" => strtotime(JWT_EXPIRE)
        ], JWT_SECRET);

        Response::ok([
            "message" => "Login bem-sucedido",
            "token" => $token,
            "user" => [
                "id" => $user['id'],
                "username" => $user['username']
            ]
        ]);
    }
}