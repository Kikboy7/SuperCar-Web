<?php
session_start();

// On supprime uniquement la session admin.
unset($_SESSION['admin']);

header("Location: login.php");
exit();
?>
