<?php
// Ce fichier protege les pages admin.
// Si l'administrateur n'est pas connecte, il retourne vers login.php.
require_once __DIR__ . '/../../includes/bootstrap.php';

include __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$adminExiste = false;
if (isset($_SESSION['admin'])) {
    $verificationAdmin = $pdo->prepare('SELECT id_admin FROM admin WHERE id_admin = ?');
    $verificationAdmin->execute([$_SESSION['admin']]);
    $adminExiste = (bool) $verificationAdmin->fetchColumn();
}
if (!$adminExiste) {
    unset($_SESSION['admin']);
    header("Location: login.php");
    exit();
}
?>
