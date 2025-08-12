<?php

//remover quando tiver em producao
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/config.php';
require __DIR__ . '/config/cors.php';

// Define o Content-Type padrão como JSON
header('Content-Type: application/json; charset=utf-8');

// Configuração de timezone
date_default_timezone_set('America/Sao_Paulo');

// Carrega as rotas
require __DIR__ . '/routes/api.php';