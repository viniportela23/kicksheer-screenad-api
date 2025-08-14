<?php

// esta parte cuida da logica das chamadas a listas

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Adicao.php';
require_once __DIR__ . '/../utils/SalvaArquivos.php';
require_once __DIR__ . '/../utils/Response.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key; // Adicione isso aqui

class AdicaoController extends BaseController {

    public function anunciantes($dataToken) {

        if ($this->requestMethod !== 'POST') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        // faz a requisicao para SalvaArquivos.php aonde salva o arquivo e depois recebe o nome q ficou salvo
        $SalvaArquivos = new SalvaArquivos();
        $resultSalvaArquivos = $SalvaArquivos->saveFile($_FILES['arquivo'], 'anunciantes');

        if ($resultSalvaArquivos['success'] !== true) {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao salvar o arquivo"
            ]);
        }

        $userModel = new Adicao($this->db);
        $AdicaoAnuncios = $userModel->InsertAnunciantes($data, $resultSalvaArquivos['file_name'], $dataToken['id']);

        if ($AdicaoAnuncios['success'] ===  true) {
            Response::ok([
                "message" => "Anúnciante inserido com sucesso!",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao inseriri anúncio"
            ]);
  
        }
    }
}
?>