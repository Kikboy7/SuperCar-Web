<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify()) { header('Location: dashboard.php'); exit(); }

// On supprime uniquement la session admin.
unset($_SESSION['admin']);
session_regenerate_id(true);

header("Location: login.php");
exit();
?>
