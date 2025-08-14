<?php

// esta parte cuida da logica das chamadas a listas

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Busca.php';
require_once __DIR__ . '/../utils/Response.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key; // Adicione isso aqui

class ListaController extends BaseController {

    public function anunciantes($data) {

        if ($this->requestMethod !== 'POST') {
            Response::methodNotAllowed();
            return;
        }
        $userModel = new Buscas($this->db);
        $ListaAnuncios = $userModel->ConsultaCompletaBaseID($data['id'], 'anuncios');

        if (!empty($ListaAnuncios['data']) ) {
            Response::ok([
                "dados" => $ListaAnuncios['data'],
                "token" => $data['token']
            ]);
        }
        elseif ($ListaAnuncios['message'] === 'Nenhum dado encontrado') {
            Response::ok([
                "message" => "Nenhum dado encontrado",
                "token" => $data['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao consultar lista"
            ]);
  
        }
    }
    public function prodCardapio($data) {

        if ($this->requestMethod !== 'POST') {
            Response::methodNotAllowed();
            return;
        }
        $userModel = new Buscas($this->db);
        $ListaProd = $userModel->ConsultaCompletaBaseID($data['id'], 'prod_Cardapio');

        if (!empty($ListaProd['data']) ) {
            Response::ok([
                "dados" => $ListaProd['data'],
                "token" => $data['token']
            ]);
        }
        elseif ($ListaProd['message'] === 'Nenhum dado encontrado') {
            Response::ok([
                "message" => "Nenhum dado encontrado",
                "token" => $data['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao consultar lista"
            ]);
  
        }
    }
    public function layoutCardapio($data) {

        if ($this->requestMethod !== 'POST') {
            Response::methodNotAllowed();
            return;
        }
        $userModel = new Buscas($this->db);
        $Listalayout = $userModel->ConsultaCompletaBaseStatus('1', 'layout_Cardapio');

        if (!empty($Listalayout['data']) ) {
            Response::ok([
                "dados" => $Listalayout['data'],
                "token" => $data['token']
            ]);
        }
        elseif ($Listalayout['message'] === 'Nenhum dado encontrado') {
            Response::ok([
                "message" => "Nenhum dado encontrado",
                "token" => $data['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao consultar lista"
            ]);
  
        }
    }
    public function usuarios($data) {

        if ($this->requestMethod !== 'POST') {
            Response::methodNotAllowed();
            return;
        }
        if ($data['nivel'] !== 1) {
            Response::unauthorized();
            return;
        }
        $userModel = new Buscas($this->db);
        $ListaUsuarios = $userModel->ConsultaCompleta('users');

        if (!empty($ListaUsuarios['data']) ) {
            Response::ok([
                "dados" => $ListaUsuarios,
                "token" => $data['token']
            ]);
        }
        elseif ($ListaUsuarios['message'] === 'Nenhum dado encontrado') {
            Response::ok([
                "message" => "Nenhum dado encontrado",
                "token" => $data['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao consultar lista"
            ]);
  
        }
    }
}
?>