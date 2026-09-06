<?php
/**
 * essai.php - Ancienne page de demande d'essai.
 * Elle a ete remplacee par demande_essai.php (formulaire plus complet).
 * Pour eviter de dupliquer du code, cette page redirige simplement vers
 * la nouvelle page en conservant la voiture choisie dans l'URL.
 */
$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? '?id=' . (int) $_GET['id'] : '';
header("Location: demande_essai.php" . $id);
exit();
?>