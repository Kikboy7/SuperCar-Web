<?php
/** Outil CLI uniquement ; ne jamais exposer les identifiants dans Git. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit(); }
require_once __DIR__ . '/../includes/bootstrap.php';
$identifiant = $argv[1] ?? '';
$motDePasse = getenv('SUPERCAR_ADMIN_PASSWORD');
if ($identifiant === '' || mb_strlen($identifiant) > 100 || !is_string($motDePasse) || !mb_check_encoding($motDePasse, 'UTF-8') || str_contains($motDePasse, "\0") || mb_strlen($motDePasse) < 12 || strlen($motDePasse) > 72 || $motDePasse === 'admin123') {
    fwrite(STDERR, "Utilisation : SUPERCAR_ADMIN_PASSWORD défini (12 caractères minimum, 72 octets maximum), puis php tools/creer-admin.php identifiant\n");
    exit(1);
}
$hash = password_hash($motDePasse, PASSWORD_DEFAULT);
if ($identifiant === '--hash') { echo $hash . PHP_EOL; exit(); }
require_once __DIR__ . '/../includes/db.php';
$stmt = $pdo->prepare('INSERT INTO admin (identifiant, mot_de_passe) VALUES (?, ?) ON DUPLICATE KEY UPDATE mot_de_passe = ?');
$stmt->execute([$identifiant, $hash, $hash]);
echo "Compte administrateur créé ou mis à jour. Effacez ensuite la variable SUPERCAR_ADMIN_PASSWORD.\n";
