<?php

// esta parte cuida da logica das chamadas a listas

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Busca.php';
require_once __DIR__ . '/../utils/Response.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key; // Adicione isso aqui

class ListaController extends BaseController {

    public function anunciantes($dataToken) {

        if ($this->requestMethod !== 'GET') {
            Response::methodNotAllowed();
            return;
        }

        $status = $_GET['status'] ?? null;
        $id = $_GET['id'] ?? null;

        $userModel = new Buscas($this->db);

        if (!empty($id)) {
            $ListaAnuncios = $userModel->ConsultaCompletaBaseIDUserIDTabela($id, $dataToken['id'], 'anuncios');

        } elseif (!empty($status)) {
            $ListaAnuncios = $userModel->ConsultaCompletaBaseIDUserStatus($status, $dataToken['id'], 'anuncios');

        } else {
            $ListaAnuncios = $userModel->ConsultaCompletaBaseIDUser($dataToken['id'], 'anuncios');
        }


        if (!empty($ListaAnuncios['data']) ) {
            Response::ok([
                "dados" => $ListaAnuncios['data'],
                "token" => $dataToken['token']
            ]);
        }
        elseif ($ListaAnuncios['message'] === 'Nenhum dado encontrado') {
            Response::ok([
                "message" => "Nenhum dado encontrado",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao consultar lista"
            ]);
  
        }
    }
    public function prodCardapio($dataToken) {

        if ($this->requestMethod !== 'GET') {
            Response::methodNotAllowed();
            return;
        }

        $status = $_GET['status'] ?? null;
        $id = $_GET['id'] ?? null;

        $userModel = new Buscas($this->db);

        if (!empty($id)) {
            $ListaProd = $userModel->ConsultaCompletaBaseIDUserIDTabela($id, $dataToken['id'], 'prod_Cardapio');
        } elseif (!empty($status)) {
            $ListaProd = $userModel->ConsultaCompletaBaseIDUserStatus($status, $dataToken['id'], 'prod_Cardapio');

        } else {
            $ListaProd = $userModel->ConsultaCompletaBaseIDUser($dataToken['id'], 'prod_Cardapio');
        }

        if (!empty($ListaProd['data']) ) {
            Response::ok([
                "dados" => $ListaProd['data'],
                "token" => $dataToken['token']
            ]);
        }
        elseif ($ListaProd['message'] === 'Nenhum dado encontrado') {
            Response::ok([
                "message" => "Nenhum dado encontrado",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao consultar lista"
            ]);
  
        }
    }
    public function layoutCardapio($dataToken) {

        if ($this->requestMethod !== 'GET') {
            Response::methodNotAllowed();
            return;
        }

        $status = $_GET['status'] ?? null;
        $id = $_GET['id'] ?? null;

        $userModel = new Buscas($this->db);

        if (!empty($id)) {
            $Listalayout = $userModel->ConsultaCompletaBaseIDUserIDTabela($id, $dataToken['id'], 'layout_Cardapio');
        } elseif (!empty($status)) {
            $Listalayout = $userModel->ConsultaCompletaBaseIDUserStatus($status, $dataToken['id'], 'layout_Cardapio');

        } else {
            $Listalayout = $userModel->ConsultaCompletaBaseIDUser($dataToken['id'], 'layout_Cardapio');
        }

        if (!empty($Listalayout['data']) ) {
            Response::ok([
                "dados" => $Listalayout['data'],
                "token" => $dataToken['token']
            ]);
        }
        elseif ($Listalayout['message'] === 'Nenhum dado encontrado') {
            Response::ok([
                "message" => "Nenhum dado encontrado",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao consultar lista"
            ]);
  
        }
    }
    public function usuario($dataToken) {

        if ($this->requestMethod !== 'GET') {
            Response::methodNotAllowed();
            return;
        }

        $userModel = new Buscas($this->db);
        $ListaUsuario = $userModel->ConsultaCompletaBaseIDTabela($dataToken['id'], 'users');
        $ListaNotificacoes = $userModel->ConsultaCompletaBaseIDUser($dataToken['id'], 'notificacoes');

        $contador = 0;

        if (!empty($ListaNotificacoes['data']) ) {
            foreach ($ListaNotificacoes['data'] as $item) {
                if ($item['status'] == 1) {
                    $contador++;
                }
            }
        }

        if (!empty($ListaUsuario['data']) ) {
            unset($ListaUsuario['data'][0]['password']);
            Response::ok([
                "dados" => $ListaUsuario['data'],
                "notificacoes" => $contador,               
                "token" => $dataToken['token']
            ]);
        }
        elseif ($ListaUsuario['message'] === 'Nenhum dado encontrado') {
            Response::ok([
                "message" => "Nenhum dado encontrado",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao consultar lista"
            ]);
  
        }
    }
    public function notificacoes($dataToken) {

        if ($this->requestMethod !== 'GET') {
            Response::methodNotAllowed();
            return;
        }

        $userModel = new Buscas($this->db);
        $ListaNotificacoes = $userModel->ConsultaCompletaBaseIDUser($dataToken['id'], 'notificacoes');


        if (!empty($ListaNotificacoes['data']) ) {
            Response::ok([
                "dados" => $ListaNotificacoes['data'],
                "token" => $dataToken['token']
            ]);
        }
        elseif ($ListaUsuario['message'] === 'Nenhum dado encontrado') {
            Response::ok([
                "message" => "Nenhum dado encontrado",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao consultar lista"
            ]);
  
        }
    }


}

?>