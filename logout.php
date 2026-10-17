<?php
/**
 * logout.php - Deconnexion du client.
 * Supprime uniquement les informations de session du client connecte.
 */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify()) { header('Location: compte.php'); exit(); }

unset($_SESSION['client']);
session_regenerate_id(true);

header("Location: index.php");
exit();
?>
