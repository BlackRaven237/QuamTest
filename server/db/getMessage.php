<?php
// backend/get_messages.php
header('Content-Type: application/json');

$pdo = new PDO('mysql:host=localhost;dbname=votre_bdd;charset=utf8', 'root', '');

// Récupérer tous les messages
$stmt = $pdo->query("SELECT id, date, nom, sujet FROM messages ORDER BY date DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renvoyer les données au format JSON
echo json_encode($messages);
