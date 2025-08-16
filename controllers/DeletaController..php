<?php

// esta parte cuida da logica das chamadas a listas

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Deleta.php';
require_once __DIR__ . '/../utils/SalvaArquivos.php';
require_once __DIR__ . '/../utils/Response.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key; // Adicione isso aqui

class DeletaController extends BaseController {

    public function anunciantes($dataToken) {

        if ($this->requestMethod !== 'PUT') {
            Response::methodNotAllowed();
            return;
        }

        $input = file_get_contents('php://input'); // Retorna "id=1&nome=teste..."

        parse_str($input, $data);

        $resultSalvaArquivos['file_name'] = null;

        if (!empty($_FILES['arquivo'])) {
            // faz a requisicao para SalvaArquivos.php aonde salva o arquivo e depois recebe o nome q ficou salvo
            $SalvaArquivos = new SalvaArquivos();
            $resultSalvaArquivos = $SalvaArquivos->saveFile($_FILES['arquivo'], 'anunciantes');

            if ($resultSalvaArquivos['success'] !== true) {
                Response::unprocessableEntity([
                    "message" => "O correu um erro ao salvar o arquivo"
                ]);
            }

        }

        $userModel = new Update($this->db);
        $EdicaoAnuncios = $userModel->UpdateAnunciantes($data, $dataToken['id'], $resultSalvaArquivos['file_name']);

        if ($EdicaoAnuncios['success'] ===  true) {
            Response::ok([
                "message" => "Anúnciante atualizado com sucesso!",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao atualizar anúncio"
            ]);
  
        }
    }
    public function prodCardapio($dataToken) {

        if ($this->requestMethod !== 'PUT') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new Update($this->db);
        $UpdateProdCardapio = $userModel->UpdateProdCardapio($data, $dataToken['id']);

        if ($UpdateProdCardapio['success'] ===  true) {
            Response::ok([
                "message" => "Produto do cardapio inserido com sucesso!",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => $UpdateProdCardapio
            ]);
  
        }
    }
    public function layoutCardapio($dataToken) {

        if ($this->requestMethod !== 'PUT') {
            Response::methodNotAllowed();
            return;
        }

        $input = file_get_contents('php://input'); // Retorna "id=1&nome=teste..."

        parse_str($input, $data);

        $resultSalvaArquivos['file_name'] = null;

        if (!empty($_FILES['arquivo'])) {
            // faz a requisicao para SalvaArquivos.php aonde salva o arquivo e depois recebe o nome q ficou salvo
            $SalvaArquivos = new SalvaArquivos();
            $resultSalvaArquivos = $SalvaArquivos->saveFile($_FILES['arquivo'], 'layouts');

            if ($resultSalvaArquivos['success'] !== true) {
                Response::unprocessableEntity([
                    "message" => "O correu um erro ao salvar o arquivo"
                ]);
            }

        }

        $userModel = new Update($this->db);
        $UpdateLayoutCardapio = $userModel->UpdateLayoutCardapio($data, $dataToken['id'], $resultSalvaArquivos['file_name']);

        if ($UpdateLayoutCardapio['success'] ===  true) {
            Response::ok([
                "message" => "Produto atualizado com sucesso!",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao atualizar produto"
            ]);
  
        }
    }

}
?>