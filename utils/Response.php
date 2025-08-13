<?php

// pagina voltada apenas para fazer as respostas 

class Response {
    public static function ok($data = []) {
        http_response_code(200);
        echo json_encode($data);
        exit;
    }

    public static function created($data = []) {
        http_response_code(201);
        echo json_encode($data);
        exit;
    }

    public static function notData($data = []) {
        http_response_code(204);
        echo json_encode($data);
        exit;
    }

    public static function unauthorized($data = ['message' => 'Não autorizado']) {
        http_response_code(401);
        echo json_encode($data);
        exit;
    }

    public static function forbidden($data = ['message' => 'Acesso proibido']) {
        http_response_code(403);
        echo json_encode($data);
        exit;
    }

    public static function notFound($data = ['message' => 'Recurso não encontrado']) {
        http_response_code(404);
        echo json_encode($data);
        exit;
    }

    public static function methodNotAllowed($data = ['message' => 'Método não permitido']) {
        http_response_code(405);
        echo json_encode($data);
        exit;
    }

    public static function unprocessableEntity($data = ['message' => 'Entidade não processável']) {
        http_response_code(422);
        echo json_encode($data);
        exit;
    }

    public static function serverError($data = ['message' => 'Erro interno do servidor']) {
        http_response_code(500);
        echo json_encode($data);
        exit;
    }
}
?>