<?php
/**
 * logout.php - Deconnexion du client.
 * Supprime uniquement les informations de session du client connecte.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['client']);

header("Location: index.php");
exit();
?>