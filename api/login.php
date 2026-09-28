<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;

$secretKey = "iYs1z2XdtfUy6nyREBbVZ5BNr1dY6rxPq7mQuNRSJI9";

// 1. Données de test
$users = [
    [
        "id" => 1,
        "email" => "user1@geotech.fr",
        "password" => "password123",
        "role" => "MANAGER"
    ],
    [
        "id" => 2,
        "email" => "tech1@geotech.fr",
        "password" => "secure456",
        "role" => "TECHNICIEN"
    ]
];

// Récupération des données JSON envoyées
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

// Recherche de l'utilisateur
$currentUser = null;
foreach ($users as $user) {
    if ($user['email'] === $email && $user['password'] === $password) {
        $currentUser = $user;
        break;
    }
}

// Identifiants incorrects
if (!$currentUser) {
    http_response_code(401);
    echo json_encode(["error" => "Identifiants incorrects."]);
    exit();
}

// 2. Génération du token (expiration : 15 minutes)
$payload = [
    "id" => $currentUser['id'],
    "email" => $currentUser['email'],
    "role" => $currentUser['role'],
    "iat" => time(),
    "exp" => time() + (15 * 60)
];

$jwt = JWT::encode($payload, $secretKey, 'HS256');

echo json_encode(["token" => $jwt]);