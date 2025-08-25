<?php
$allowedOrigins = [
    'https://screenad.kicksheer.com.br',
    'http://localhost:3000',
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    header("Access-Control-Allow-Origin: https://screenad.kicksheer.com.br");
}

header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, Token-Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Credentials: true"); // IMPORTANTE: permitir credenciais


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}