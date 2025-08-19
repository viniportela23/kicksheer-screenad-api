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

        $nome = $_POST['nome'] ?? null;
        $tempo = $_POST['tempo'] ?? null;
        $data_finalizacao = $_POST['data_finalizacao'] ?? null;
        $status = $_POST['status'] ?? null;

        $data = [
            'nome' => $nome,
            'tempo' => $tempo,
            'data_finalizacao' => $data_finalizacao,
            'status' => $status,
        ];

        if (empty($_FILES['arquivo'])) {
            Response::unprocessableEntity([
                "message" => "Arquivo não enviado"
            ]);
        }

        // faz a requisicao para SalvaArquivos.php aonde salva o arquivo e depois recebe o nome q ficou salvo
        $SalvaArquivos = new SalvaArquivos();
        $resultSalvaArquivos = $SalvaArquivos->saveFile($_FILES['arquivo'], 'anunciantes');

        if ($resultSalvaArquivos['success'] !== true) {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao salvar o arquivo"
            ]);
        }

        $userModel = new Adicao($this->db);
        $InsertAnunciantes = $userModel->InsertAnunciantes($data, $resultSalvaArquivos['file_name'], $dataToken['id']);

        if ($InsertAnunciantes['success'] ===  true) {
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
    public function prodCardapio($dataToken) {

        if ($this->requestMethod !== 'POST') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new Adicao($this->db);
        $InsertProdCardapio = $userModel->InsertProdCardapio($data, $dataToken['id']);

        if ($InsertProdCardapio['success'] ===  true) {
            Response::ok([
                "message" => "Produto do cardapio atualizado com sucesso!",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao atualizar produto do cardapio"
            ]);
  
        }
    }
    public function layoutCardapio($dataToken) {

        if ($this->requestMethod !== 'POST') {
            Response::methodNotAllowed();
            return;
        }

        $nome = $_POST['nome'] ?? null;
        $status = $_POST['status'] ?? null;
        $tempo = $_POST['tempo'] ?? null;
        $cor_titulo = $_POST['corTitulo'] ?? null;
        $cor_fundo = $_POST['corFundo'] ?? null;
        $cor_nome = $_POST['corNome'] ?? null;
        $cor_preco = $_POST['corPreco'] ?? null;

        $data = [
            'nome' => $nome,
            'status' => $status,
            'tempo' => $tempo,
            'cor_titulo' => $cor_titulo,
            'cor_fundo' => $cor_fundo,
            'cor_nome' => $cor_nome,
            'cor_preco' => $cor_preco,
        ];

        if (empty($_FILES['arquivo'])) {
            Response::unprocessableEntity([
                "message" => "Arquivo não enviado"
            ]);
        }

        // faz a requisicao para SalvaArquivos.php aonde salva o arquivo e depois recebe o nome q ficou salvo
        $SalvaArquivos = new SalvaArquivos();
        $resultSalvaArquivos = $SalvaArquivos->saveFile($_FILES['arquivo'], 'layouts');

        if ($resultSalvaArquivos['success'] !== true) {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao salvar o arquivo"
            ]);
        }

        $userModel = new Adicao($this->db);
        $InsertLayoutCardapio = $userModel->InsertLayoutCardapio($data, $resultSalvaArquivos['file_name'], $dataToken['id']);

        if ($InsertLayoutCardapio['success'] ===  true) {
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