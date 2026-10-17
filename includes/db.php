<?php
require_once __DIR__ . '/bootstrap.php';
try {
    foreach (['db_host', 'db_port', 'db_name', 'db_user', 'db_password'] as $cle) {
        if (configuration($cle) === null) throw new RuntimeException('Configuration de base de données absente : ' . $cle);
    }
    $pdo = new PDO('mysql:host=' . configuration('db_host') . ';port=' . (int) configuration('db_port') . ';dbname=' . configuration('db_name') . ';charset=utf8mb4', configuration('db_user'), configuration('db_password'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]);
} catch (Throwable $exception) {
    error_log('SuperCar : connexion à la base impossible. ' . $exception->getMessage());
    if (PHP_SAPI === 'cli') throw $exception;
    http_response_code(503);
    exit('<h1>Service temporairement indisponible</h1><p>Veuillez réessayer plus tard.</p>');
}
