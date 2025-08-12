<?php
class JWT {
    public static function encode($payload, $secret) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);
        
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function decode($jwt, $secret) {
        $tokenParts = explode('.', $jwt);
        if (count($tokenParts) !== 3) {
            throw new Exception("Token inválido");
        }

        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $tokenParts;
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignatureToVerify = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        if ($base64UrlSignature !== $base64UrlSignatureToVerify) {
            throw new Exception("Assinatura inválida");
        }
        
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $base64UrlPayload)));
        
        if (isset($payload->exp) && $payload->exp < time()) {
            throw new Exception("Token expirado");
        }
        
        return $payload;
    }
}