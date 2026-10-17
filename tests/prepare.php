<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit(); }
require_once __DIR__ . '/../includes/db.php';
$nom = 'supercar_recette_' . date('Ymd_His') . '_' . bin2hex(random_bytes(2));
$pdo->exec('CREATE DATABASE `' . $nom . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci');
$pdo->exec('USE `' . $nom . '`');
$pdo->exec(file_get_contents(__DIR__ . '/../schema.sql'));
$pdo->exec(file_get_contents(__DIR__ . '/../contenu_site.sql'));
$secret = bin2hex(random_bytes(16));
$stmt = $pdo->prepare('INSERT INTO admin (identifiant, mot_de_passe) VALUES (?, ?)');
$stmt->execute(['recette_admin', password_hash($secret, PASSWORD_DEFAULT)]);
file_put_contents(__DIR__ . '/.audit-state.json', json_encode(['database' => $nom, 'admin' => 'recette_admin', 'password' => $secret], JSON_PRETTY_PRINT));
echo $nom . PHP_EOL;
