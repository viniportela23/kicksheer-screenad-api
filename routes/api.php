<?php
require_once __DIR__ . '/../controllers/AuthController.php';

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

// Roteamento
switch ($urlParts[0]) {
    case 'auth':
        $authController = new AuthController();
        switch ($urlParts[1] ?? '') {
            case 'login':
                $authController->login();
                break;
            default:
                http_response_code(404);
                echo json_encode(["message" => "Endpoint não encontrado"]);
                break;
        }
        break;
    default:
        http_response_code(404);
        echo json_encode(["message" => "Rota não encontrada"]);
        break;
}
?>