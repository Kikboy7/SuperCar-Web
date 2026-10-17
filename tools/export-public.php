<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit(); }
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/contact_config.php';
echo '-- Instantané du contenu PUBLIC de SuperCar au ' . date('d/m/Y') . ".\n-- Aucun compte, mot de passe, message ou demande d’essai.\n-- Base neuve : sélectionner la base cible, importer schema.sql, puis ce fichier.\n-- Contenu existant : sauvegarder avant import, car les valeurs correspondantes sont remplacées.\nSET NAMES utf8mb4;\nSTART TRANSACTION;\n\n";
foreach (['marque' => 'id_marque', 'voiture' => 'id_voiture', 'voiture_image' => 'id_image', 'services' => 'id_services', 'contenu_accueil' => 'id_contenu', 'contenu_contact' => 'cle'] as $table => $primaire) {
    $lignes = $pdo->query('SELECT * FROM `' . $table . '` ORDER BY `' . $primaire . '`')->fetchAll();
    if ($table === 'contenu_contact') $lignes = array_values(array_filter($lignes, fn($ligne) => array_key_exists($ligne['cle'], contact_defauts())));
    if (!$lignes) continue;
    $colonnes = array_keys($lignes[0]);
    echo '-- ' . $table . ' : ' . count($lignes) . " lignes.\nINSERT INTO `$table` (`" . implode('`, `', $colonnes) . "`) VALUES\n";
    $valeurs = [];
    foreach ($lignes as $ligne) $valeurs[] = '(' . implode(', ', array_map(fn($valeur) => $valeur === null ? 'NULL' : $pdo->quote((string) $valeur), array_values($ligne))) . ')';
    echo implode(",\n", $valeurs) . "\nON DUPLICATE KEY UPDATE " . implode(', ', array_map(fn($colonne) => '`' . $colonne . '` = VALUES(`' . $colonne . '`)', array_filter($colonnes, fn($colonne) => $colonne !== $primaire))) . ";\n\n";
}
echo "COMMIT;\n";
