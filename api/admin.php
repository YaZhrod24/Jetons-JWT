<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../AuthMiddleware.php';

// 1. Vérification du token
$currentUser = AuthMiddleware::verifyToken();

// 2. Vérification du rôle requis (MANAGER uniquement)
AuthMiddleware::requireRole($currentUser, 'MANAGER');

// 3. Réponse en cas de succès
echo json_encode([
    "status" => "Succès",
    "message" => "Accès autorisé au back-office"
]);