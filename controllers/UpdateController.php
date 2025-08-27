<?php

// esta parte cuida da logica das chamadas a listas

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Update.php';
require_once __DIR__ . '/../utils/SalvaArquivos.php';
require_once __DIR__ . '/../utils/Response.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key; // Adicione isso aqui

class UpdateController extends BaseController {

    public function anunciantes($dataToken) {

        if ($this->requestMethod !== 'POST') {
            Response::methodNotAllowed();
            return;
        }

        $id = $_POST['id'] ?? null;
        $nome = $_POST['nome'] ?? null;
        $tempo = $_POST['tempo'] ?? null;
        $data_finalizacao = $_POST['data_finalizacao'] ?? null;
        $status = $_POST['status'] ?? null;

        $data = [
            'id' => $id,
            'nome' => $nome,
            'tempo' => $tempo,
            'data_finalizacao' => $data_finalizacao,
            'status' => $status,
        ];

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
                "message" => $EdicaoAnuncios
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
                "message" => "Produto do cardapio atualizado com sucesso!",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao atualizar produto"
            ]);
  
        }
    }
    public function layoutCardapio($dataToken) {

        if ($this->requestMethod !== 'POST') {
            Response::methodNotAllowed();
            return;
        }

        $id = $_POST['id'] ?? null;
        $nome = $_POST['nome'] ?? null;
        $status = $_POST['status'] ?? null;
        $tempo = $_POST['tempo'] ?? null;
        $cor_titulo = $_POST['corTitulo'] ?? null;
        $cor_fundo = $_POST['cor_fundo'] ?? null;
        $cor_nome = $_POST['cor_nome'] ?? null;
        $cor_preco = $_POST['cor_preco'] ?? null;

        $data = [
            'id' => $id,
            'nome' => $nome,
            'status' => $status,
            'tempo' => $tempo,
            'cor_titulo' => $cor_titulo,
            'cor_fundo' => $cor_fundo,
            'cor_nome' => $cor_nome,
            'cor_preco' => $cor_preco,
        ];

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
                "message" => "Layout atualizado com sucesso!",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao atualizar layout"
            ]);
  
        }
    }
    public function notificacoes($dataToken) {

        if ($this->requestMethod !== 'PUT') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new Update($this->db);

        if (isset($data["id"]) && is_array($data["id"])) {
            foreach ($data["id"] as $id) {
                $UpdateNotificacoes = $userModel->UpdateNotificacoes(['id' => $id, 'status' => $data["status"]], $dataToken['id']);
            }
        } else if (isset($data["id"])) {
            $UpdateNotificacoes = $userModel->UpdateNotificacoes($data, $dataToken['id']);
        } else {
            Response::unprocessableEntity([
                "message" => "ID é obrigatório"
            ]);        
        }


        if ($UpdateNotificacoes['success'] ===  true) {
            Response::ok([
                "message" => "Notificacao atualizada com sucesso!",
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