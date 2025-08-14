<?php

// esta pagina faz a recepsao e separacao das requisicoes

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

// Roteamento
switch ($urlParts[0]) {
    case 'auth':
        require_once __DIR__ . '/../controllers/AuthController.php';

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
        
    case 'lista':
        // Primeiro validar o token JWT
        require_once __DIR__ . '/../controllers/BaseController.php';
        $baseController = new BaseController();
        $novoToken = $baseController->validateJWT(); // Isso vai parar a execução se o token for inválido
        
        // Se chegou aqui, o token é válido, pode continuar
        require_once __DIR__ . '/../controllers/ListaController.php';

        $listaController = new ListaController();
        switch ($urlParts[1] ?? '') {
            case 'anunciantes':
                $listaController->anunciantes($novoToken);
                break;
            case 'prodCardapio':
                $listaController->prodCardapio($novoToken);
                break;
            case 'layoutCardapio':
                $listaController->layoutCardapio($novoToken);
                break;
            case 'usuarios':
                $listaController->usuarios($novoToken);
                break;
            default:
                http_response_code(404);
                echo json_encode(["message" => "Endpoint de lista não encontrado"]);
                break;
        }
        break;
    case 'adiciona':
        // Primeiro validar o token JWT
        require_once __DIR__ . '/../controllers/BaseController.php';
        $baseController = new BaseController();
        $novoToken = $baseController->validateJWT(); // Isso vai parar a execução se o token for inválido
        
        // Se chegou aqui, o token é válido, pode continuar
        require_once __DIR__ . '/../controllers/AdicaoController.php';

        $AdicaoController = new AdicaoController();
        switch ($urlParts[1] ?? '') {
            case 'anunciantes':
                $AdicaoController->anunciantes($novoToken);
                break;
            case 'prodCardapio':
                $AdicaoController->prodCardapio($novoToken);
                break;
            case 'layoutCardapio':
                $AdicaoController->layoutCardapio($novoToken);
                break;
            case 'usuarios':
                $AdicaoController->usuarios($novoToken);
                break;
            default:
                http_response_code(404);
                echo json_encode(["message" => "Endpoint de lista não encontrado"]);
                break;
        }
        break;
    default:
        http_response_code(404);
        echo json_encode(["message" => "Rota não encontrada"]);
        break;
}