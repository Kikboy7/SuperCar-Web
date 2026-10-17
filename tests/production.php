<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit(); }
require_once __DIR__ . '/../includes/db.php';
$etat = json_decode(file_get_contents(__DIR__ . '/.audit-state.json'), true);
if (!preg_match('/^supercar_recette_[0-9_]+[a-f0-9]{4}$/D', $etat['database'])) exit(1);
$pdo->exec('USE `' . $etat['database'] . '`');
$stmt = $pdo->prepare('INSERT INTO admin (identifiant, mot_de_passe) VALUES (?, ?) ON DUPLICATE KEY UPDATE mot_de_passe = ?');
$hash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt->execute(['recette_mot_de_passe_public', $hash, $hash]);
function login_production($page, $identifiant, $password) {
    $curl = curl_init('http://127.0.0.1:8766/' . $page);
    curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_HEADER => true, CURLOPT_COOKIEFILE => '', CURLOPT_TIMEOUT => 10]);
    $html = curl_exec($curl);
    preg_match('/name="csrf" value="([a-f0-9]{64})"/', $html, $token);
    if (!isset($token[1])) throw new RuntimeException('Formulaire indisponible.');
    curl_setopt($curl, CURLOPT_POST, true); curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(['csrf' => $token[1], 'identifiant' => $identifiant, 'password' => $password]));
    $html = curl_exec($curl); $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE); curl_close($curl);
    return [$status, $html];
}
[$status, $html] = login_production('admin/login.php', 'recette_mot_de_passe_public', 'admin123');
if ($status !== 200 || !str_contains($html, 'incorrect')) throw new RuntimeException('Compte public non bloqué.');
echo "OK : compte administrateur au mot de passe public refusé en production.\n";
[$status] = login_production('admin/login.php', $etat['admin'], $etat['password']);
if ($status !== 302) throw new RuntimeException('Administrateur privé indisponible.');
echo "OK : administrateur privé accepté en production.\n";
try {
    for ($i = 0; $i < 11; $i++) [$status] = login_production('login.php', 'recette_inconnue', 'mauvais');
    if ($status !== 429) throw new RuntimeException('Limitation absente.');
    echo "OK : HTTP 429 au onzième échec, même avec onze sessions différentes.\n";
} finally {
    // Retirer uniquement les compteurs générés pour 127.0.0.1 par cette recette.
    foreach (['admin', 'client'] as $zone) {
        $fichier = sys_get_temp_dir() . '/supercar-auth-' . hash('sha256', realpath(__DIR__ . '/../includes') . $zone . '127.0.0.1') . '.json';
        if (is_file($fichier)) unlink($fichier);
    }
}
$pdo->exec("DELETE FROM admin WHERE identifiant = 'recette_mot_de_passe_public'");
