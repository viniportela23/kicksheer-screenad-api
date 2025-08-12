<?php
// Carrega variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Configurações globais
define('JWT_SECRET', $_ENV['JWT_SECRET']);
define('JWT_EXPIRE', $_ENV['JWT_EXPIRE']); // Ex: '+1 hour'
?>