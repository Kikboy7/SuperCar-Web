<?php
// Ce fichier protege les pages admin.
// Si l'administrateur n'est pas connecte, il retourne vers login.php.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
