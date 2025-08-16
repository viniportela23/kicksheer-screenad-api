<?php

// esta parte cuida da logica das chamadas a listas

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Deleta.php';
require_once __DIR__ . '/../utils/Response.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key; // Adicione isso aqui

class DeletaController extends BaseController {

    public function anunciantes($dataToken) {

        if ($this->requestMethod !== 'DELETE') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new Deletar($this->db);
        $DeletarBaseIDUserIDTabel = $userModel->DeletarBaseIDUserIDTabel($data['id'], $dataToken['id'], 'anuncios');

        if ($DeletarBaseIDUserIDTabel['success'] ===  true) {
            Response::ok([
                "message" => "Anúnciante deletado com sucesso!",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao deletar anúncio"
            ]);
  
        }
    }
    public function prodCardapio($dataToken) {

        if ($this->requestMethod !== 'DELETE') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new Deletar($this->db);
        $DeletarBaseIDUserIDTabel = $userModel->DeletarBaseIDUserIDTabel($data['id'], $dataToken['id'], 'prod_Cardapio');

        if ($DeletarBaseIDUserIDTabel['success'] ===  true) {
            Response::ok([
                "message" => "Produto do cardapio deletado com sucesso!",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao deletar produto"
            ]);
  
        }
    }
    public function layoutCardapio($dataToken) {

        if ($this->requestMethod !== 'DELETE') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new Deletar($this->db);
        $DeletarBaseIDUserIDTabel = $userModel->DeletarBaseIDUserIDTabel($data['id'], $dataToken['id'], 'layout_Cardapio');

        if ($DeletarBaseIDUserIDTabel['success'] ===  true) {
            Response::ok([
                "message" => "Layout deletado com sucesso!",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao deletar layout"
            ]);
  
        }
    }

}
?>