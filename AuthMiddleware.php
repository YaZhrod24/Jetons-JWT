<?php
require_once __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    private static $secretKey = "iYs1z2XdtfUy6nyREBbVZ5BNr1dY6rxPq7mQuNRSJI9";

    public static function verifyToken() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(["error" => "Accès refusé. Jeton manquant ou format invalide."]);
            exit();
        }

        $token = $matches[1];

        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["error" => "Jeton invalide ou expiré."]);
            exit();
        }
    }

    public static function requireRole($user, $roleAttendu) {
        if (!isset($user['role']) || $user['role'] !== $roleAttendu) {
            http_response_code(403);
            echo json_encode(["error" => "Accès interdit. Droits insuffisants."]);
            exit();
        }
    }
}