<?php
/**
 * reservation.php - Page de garde de la zone client.
 * Les reservations sont desormais affichees dans "Mon compte" (compte.php).
 * Cette page redirige donc vers l'espace compte pour eviter la duplication.
 */
header("Location: compte.php");
exit();
