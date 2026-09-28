<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../AuthMiddleware.php';

// Vérification du token
$currentUser = AuthMiddleware::verifyToken();

// Retourne les infos de l'utilisateur connecté
echo json_encode([
    "id" => $currentUser['id'],
    "email" => $currentUser['email'],
    "role" => $currentUser['role']
]);