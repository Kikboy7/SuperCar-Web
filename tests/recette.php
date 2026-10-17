<?php
/** Recette destructive UNIQUEMENT sur la base jetable créée par prepare.php. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit(); }
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/contact_config.php';
$etat = json_decode(file_get_contents(__DIR__ . '/.audit-state.json'), true, 512, JSON_THROW_ON_ERROR);
if (!preg_match('/^supercar_recette_[0-9_]+[a-f0-9]{4}$/D', $etat['database'])) throw new RuntimeException('Base de recette obligatoire.');
$pdo->exec('USE `' . $etat['database'] . '`');
$base = 'http://127.0.0.1:8765/';
$verification = curl_init($base . 'index.php');
curl_setopt_array($verification, [CURLOPT_RETURNTRANSFER => true, CURLOPT_HEADER => true, CURLOPT_TIMEOUT => 10]);
$enteteRecette = curl_exec($verification); curl_close($verification);
if (!str_contains(strtolower((string) $enteteRecette), 'x-supercar-recette: ' . hash('sha256', $etat['database']))) throw new RuntimeException('Le serveur HTTP ne pointe pas vers la base jetable attendue. Arrêt avant toute écriture.');
$nombre = 0;
$visites = [];
function ok($condition, $libelle) {
    global $nombre;
    if (!$condition) throw new RuntimeException('ÉCHEC : ' . $libelle);
    echo 'OK ' . ++$nombre . ' : ' . $libelle . PHP_EOL;
}
function navigateur() {
    $curl = curl_init();
    curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_HEADER => true, CURLOPT_COOKIEFILE => '', CURLOPT_TIMEOUT => 20, CURLOPT_FOLLOWLOCATION => false]);
    return $curl;
}
function requete($curl, $page, $post = null) {
    global $base, $visites;
    curl_setopt($curl, CURLOPT_URL, $base . $page);
    if ($post === null) curl_setopt($curl, CURLOPT_HTTPGET, true);
    else { curl_setopt($curl, CURLOPT_POST, true); curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post)); }
    $reponse = curl_exec($curl);
    if ($reponse === false) throw new RuntimeException(curl_error($curl));
    $taille = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $body = substr($reponse, $taille); $head = substr($reponse, 0, $taille);
    $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    ok(!preg_match('/(?:Fatal error|Warning:|Deprecated:|PDOException|Uncaught|headers already sent)/i', $body) && $status < 500, $page . ' : aucune erreur serveur');
    $dom = new DOMDocument(); libxml_use_internal_errors(true); $dom->loadHTML('<?xml encoding="UTF-8">' . ($body ?: '<p></p>')); libxml_clear_errors();
    $xpath = new DOMXPath($dom);
    $visites[$page] = $status;
    return ['status' => $status, 'body' => $body, 'headers' => $head, 'xpath' => $xpath, 'token' => $xpath->evaluate('string(//input[@name="csrf"]/@value)')];
}
function poster($curl, $page, $valeurs) {
    $get = requete($curl, $page);
    return requete($curl, $page, ['csrf' => $get['token']] + $valeurs);
}
function nombre_lignes($table) { global $pdo; return (int) $pdo->query('SELECT COUNT(*) FROM `' . $table . '`')->fetchColumn(); }
$public = navigateur(); $client = navigateur(); $admin = navigateur();
foreach (['index.php', 'voitures.php', 'services.php', 'presentation.php', 'contact.php', 'login.php', 'register.php', 'admin/login.php'] as $page) {
    $get = requete($public, $page);
    ok($get['status'] === 200 && $get['xpath']->query('//h1')->length === 1, $page . ' : page lisible avec un titre principal');
    $ids = [];
    foreach ($get['xpath']->query('//*[@id]') as $element) { $ids[] = $element->getAttribute('id'); }
    ok(count($ids) === count(array_unique($ids)), $page . ' : identifiants HTML uniques');
}
foreach (['compte.php', 'demande_essai.php', 'reservation.php', 'admin/dashboard.php', 'admin/voitures.php', 'admin/services.php', 'admin/essais.php', 'admin/messages.php', 'admin/accueil.php', 'admin/contact.php'] as $page) ok(requete($public, $page)['status'] === 302, $page . ' : accès privé protégé');
foreach (['detail.php', 'service.php', 'essai.php'] as $page) {
    foreach (['', '?id=999999', '?id[]=1'] as $query) ok(requete($public, $page . $query)['status'] === 302, $page . $query . ' : identifiant invalide géré');
}
$fichiers = scandir(__DIR__ . '/../images');
foreach (['voiture' => ['id_voiture', 'detail.php', 'image'], 'services' => ['id_services', 'service.php', 'image_services']] as $table => [$id, $page, $image]) {
    foreach ($pdo->query('SELECT * FROM `' . $table . '`')->fetchAll() as $ligne) {
        $get = requete($public, $page . '?id=' . $ligne[$id]);
        ok($get['status'] === 200 && $get['xpath']->query('//h1')->length === 1, $page . ' : fiche ' . $ligne[$id]);
        ok(in_array($ligne[$image], $fichiers, true), $table . ' : image existante et casse compatible Linux');
    }
}
foreach ($pdo->query('SELECT url FROM voiture_image')->fetchAll(PDO::FETCH_COLUMN) as $image) ok(in_array($image, $fichiers, true), 'Galerie : ' . $image);
$catalogue = requete($public, 'voitures.php?recherche=BMW+M3&budget=4000000&tri=prix_desc');
ok($catalogue['xpath']->query('//article[contains(@class,"car-card")]')->length === 1 && str_contains($catalogue['body'], 'M3 Competition'), 'Recherche, budget et tri combinés');
ok(requete($public, 'voitures.php?recherche[]=x&budget[]=x&tri[]=x&marque[]=x&page[]=x')['status'] === 200, 'Paramètres du catalogue en tableau neutralisés');
$inscription = ['nom' => 'Élodie Recette', 'email' => 'recette.' . bin2hex(random_bytes(5)) . '@example.test', 'identifiant' => 'recette_' . bin2hex(random_bytes(5)), 'password' => 'UnePhraseTest-2026!', 'confirm' => 'UnePhraseTest-2026!'];
$avant = nombre_lignes('client');
requete($client, 'register.php', $inscription + ['csrf' => ['invalide']]);
ok(nombre_lignes('client') === $avant, 'Inscription sans CSRF refusée');
poster($client, 'register.php', array_replace($inscription, ['nom' => ['invalide']]));
poster($client, 'register.php', array_replace($inscription, ['password' => 'court', 'confirm' => 'court']));
ok(nombre_lignes('client') === $avant, 'Inscription : tableau et mot de passe faible refusés');
$creation = poster($client, 'register.php', $inscription);
ok(nombre_lignes('client') === $avant + 1 && $creation['status'] === 303, 'Inscription complète avec redirection anti-doublon');
$idClient = (int) $pdo->query('SELECT MAX(id_client) FROM client')->fetchColumn();
$hash = $pdo->query('SELECT mot_de_passe FROM login WHERE id_client = ' . $idClient)->fetchColumn();
ok(password_verify($inscription['password'], $hash) && $hash !== $inscription['password'], 'Mot de passe haché en base');
poster($client, 'register.php', $inscription);
ok(nombre_lignes('client') === $avant + 1, 'Doublon d’identifiant ou d’e-mail refusé');
$connexion = poster($client, 'login.php', ['identifiant' => $inscription['identifiant'], 'password' => $inscription['password']]);
ok($connexion['status'] === 303, 'Connexion client et redirection avant tout HTML');
$compte = requete($client, 'compte.php');
ok(str_contains($compte['body'], 'Élodie Recette') && str_contains(strtolower($connexion['headers']), 'httponly') && str_contains(strtolower($connexion['headers']), 'samesite=lax'), 'Compte connecté et cookie HttpOnly / SameSite');
// Le cookie peut n’être envoyé que lors de la connexion ; vérifier aussi celle-ci.
$profil = poster($client, 'compte.php', ['nom' => 'Élodie Mise à jour', 'telephone' => '+230 5250 0000', 'adresse' => 'Adresse de recette']);
ok($profil['status'] === 303 && $pdo->query('SELECT nom FROM client WHERE id_client = ' . $idClient)->fetchColumn() === 'Élodie Mise à jour', 'Modification du profil');
$profil = poster($client, 'compte.php', ['nom' => ['test'], 'telephone' => str_repeat('1', 30), 'adresse' => '']);
ok($profil['status'] === 200 && $pdo->query('SELECT nom FROM client WHERE id_client = ' . $idClient)->fetchColumn() === 'Élodie Mise à jour', 'Profil invalide sans écrasement');
$demande = ['id_voiture' => '1', 'nom' => 'Élodie Recette', 'email' => $inscription['email'], 'telephone' => '+230 5250 0000', 'adresse' => 'Adresse de recette', 'date' => date('Y-m-d', strtotime('+2 days')), 'heure' => '10:00'];
$avantEssais = nombre_lignes('essai');
foreach ([['date' => '2026-02-30'], ['email' => 'invalide'], ['date' => '2001-01-01'], ['heure' => '03:00'], ['id_voiture' => '999999'], ['nom' => ['test']]] as $invalide) poster($client, 'demande_essai.php', array_replace($demande, $invalide));
ok(nombre_lignes('essai') === $avantEssais, 'Essai : dates, e-mail, heure, voiture et tableau invalides refusés');
$essai = poster($client, 'demande_essai.php', $demande);
ok($essai['status'] === 303 && nombre_lignes('essai') === $avantEssais + 1, 'Création d’une demande d’essai');
$idEssai = (int) $pdo->query('SELECT MAX(id_essai) FROM essai')->fetchColumn();
requete($client, 'demande_essai.php'); requete($client, 'demande_essai.php'); poster($client, 'demande_essai.php', $demande);
ok(nombre_lignes('essai') === $avantEssais + 1, 'Actualisation et répétition de demande sans doublon');
$contact = poster($public, 'contact.php', ['nom' => 'Contact Recette', 'email' => 'contact@example.test', 'message' => '<script>recette</script> Message de test.']);
ok($contact['status'] === 303, 'Envoi du formulaire de contact et redirection');
$idMessage = (int) $pdo->query('SELECT MAX(id_message) FROM message')->fetchColumn();
$avantMessages = nombre_lignes('message');
requete($public, 'contact.php'); requete($public, 'contact.php');
poster($public, 'contact.php', ['nom' => ['test'], 'email' => 'invalide', 'message' => '']);
ok(nombre_lignes('message') === $avantMessages, 'Contact : actualisation et données invalides sans nouvel envoi');
$connexionAdmin = poster($admin, 'admin/login.php', ['identifiant' => $etat['admin'], 'password' => $etat['password']]);
ok($connexionAdmin['status'] === 302, 'Connexion administrateur');
foreach (['dashboard', 'voitures', 'services', 'essais', 'messages', 'accueil', 'contact'] as $page) ok(requete($admin, 'admin/' . $page . '.php')['status'] === 200, 'Administration : ' . $page);
$messages = requete($admin, 'admin/messages.php');
ok(!str_contains($messages['body'], '<script>recette</script>') && str_contains($messages['body'], '&lt;script&gt;recette'), 'Message échappé : absence de XSS stockée');
foreach (['valide', 'refuse', 'en attente'] as $statut) {
    $retour = poster($admin, 'admin/essais.php', ['id_essai' => $idEssai, 'statut' => $statut]);
    ok($retour['status'] === 303 && $pdo->query('SELECT statut FROM essai WHERE id_essai = ' . $idEssai)->fetchColumn() === $statut, 'Statut d’essai : ' . $statut);
}
poster($admin, 'admin/essais.php', ['id_essai' => $idEssai, 'statut' => 'invalide']);
ok($pdo->query('SELECT statut FROM essai WHERE id_essai = ' . $idEssai)->fetchColumn() === 'en attente', 'Statut d’essai inconnu refusé');
poster($admin, 'admin/messages.php', ['id_message' => $idMessage, 'statut_message' => 'traite', 'reponse_admin' => 'Réponse de recette.']);
ok($pdo->query('SELECT statut_message FROM message WHERE id_message = ' . $idMessage)->fetchColumn() === 'traite', 'Réponse interne et statut du message enregistrés');
$voiture = ['id_voiture' => '', 'modele' => 'Modèle Recette', 'prix' => '1234567', 'description' => 'Description de recette.', 'image' => 'bmw_m3_1.jpg', 'id_marque' => '1', 'annee' => '2026', 'carburant' => 'Hybride', 'boite' => 'Automatique', 'puissance' => '300'];
$avantVoitures = nombre_lignes('voiture');
poster($admin, 'admin/voitures.php', array_replace($voiture, ['prix' => '-1']));
poster($admin, 'admin/voitures.php', array_replace($voiture, ['image' => '../includes/db.php']));
ok(nombre_lignes('voiture') === $avantVoitures, 'Voiture : prix invalide et image hors dossier refusés');
ok(poster($admin, 'admin/voitures.php', $voiture)['status'] === 303 && nombre_lignes('voiture') === $avantVoitures + 1, 'Ajout d’une voiture');
$idVoiture = (int) $pdo->query('SELECT MAX(id_voiture) FROM voiture')->fetchColumn();
poster($admin, 'admin/voitures.php', array_replace($voiture, ['id_voiture' => $idVoiture, 'modele' => 'Modèle Recette Modifié']));
ok($pdo->query('SELECT modele FROM voiture WHERE id_voiture = ' . $idVoiture)->fetchColumn() === 'Modèle Recette Modifié', 'Modification d’une voiture');
$get = requete($admin, 'admin/voitures.php');
requete($admin, 'admin/voitures.php', ['csrf' => 'invalide', 'supprimer_voiture' => $idVoiture]);
ok(nombre_lignes('voiture') === $avantVoitures + 1, 'Suppression de voiture sans CSRF refusée');
poster($admin, 'admin/voitures.php', ['supprimer_voiture' => '1']);
ok((bool) $pdo->query('SELECT id_voiture FROM voiture WHERE id_voiture = 1')->fetchColumn(), 'Voiture liée à un essai non supprimable, transaction annulée');
poster($admin, 'admin/voitures.php', ['supprimer_voiture' => $idVoiture]);
ok(nombre_lignes('voiture') === $avantVoitures, 'Suppression de la voiture de recette');
$service = ['id_services' => '', 'nom_services' => 'Service Recette', 'prix_services' => '100', 'description_services' => 'Description de recette.', 'details_services' => 'Détails de recette.', 'points_services' => 'Point de recette', 'image_services' => 'service_essai.jpg'];
$avantServices = nombre_lignes('services');
poster($admin, 'admin/services.php', array_replace($service, ['prix_services' => '-1']));
ok(nombre_lignes('services') === $avantServices, 'Service invalide refusé');
poster($admin, 'admin/services.php', $service);
$idService = (int) $pdo->query('SELECT MAX(id_services) FROM services')->fetchColumn();
ok(nombre_lignes('services') === $avantServices + 1, 'Ajout d’un service');
poster($admin, 'admin/services.php', array_replace($service, ['id_services' => $idService, 'nom_services' => 'Service Modifié']));
ok($pdo->query('SELECT nom_services FROM services WHERE id_services = ' . $idService)->fetchColumn() === 'Service Modifié', 'Modification d’un service');
poster($admin, 'admin/services.php', ['supprimer_service' => $idService]);
ok(nombre_lignes('services') === $avantServices, 'Suppression du service de recette');
$home = $pdo->query('SELECT * FROM contenu_accueil WHERE id_contenu = 1')->fetch();
poster($admin, 'admin/accueil.php', array_replace($home, ['hero_titre_ligne1' => 'Accueil de recette']));
ok(str_contains(requete($public, 'index.php')['body'], 'Accueil de recette'), 'Textes de l’accueil dynamiques');
poster($admin, 'admin/accueil.php', $home);
$coord = contact_charger($pdo);
poster($admin, 'admin/contact.php', array_replace($coord, ['ville' => 'Ville de recette', 'contact_1_nom' => 'Élodie Recette']));
ok(str_contains(requete($public, 'index.php')['body'], 'Ville de recette') && str_contains(requete($public, 'contact.php')['body'], 'Élodie Recette'), 'Coordonnées partagées et interlocuteurs administrables');
poster($admin, 'admin/contact.php', $coord);
$get = requete($client, 'compte.php');
ok(str_contains($get['body'], 'M3 Competition'), 'Demande visible dans l’espace du seul client connecté');
ok(requete($public, 'compte.php')['status'] === 302, 'Session client non accessible à un autre navigateur');
requete($client, 'logout.php');
ok(requete($client, 'compte.php')['status'] === 200, 'Déconnexion par GET inoffensive');
$avantDeconnexion = requete($client, 'compte.php');
requete($client, 'logout.php', ['csrf' => $avantDeconnexion['token']]);
ok(requete($client, 'compte.php')['status'] === 302, 'Déconnexion client par POST et CSRF');
poster($admin, 'admin/messages.php', ['supprimer_message' => $idMessage]);
ok(nombre_lignes('message') === $avantMessages - 1, 'Suppression du message de recette');
$avantDeconnexion = requete($admin, 'admin/dashboard.php');
requete($admin, 'admin/logout.php', ['csrf' => $avantDeconnexion['token']]);
ok(requete($admin, 'admin/dashboard.php')['status'] === 302, 'Déconnexion admin');
foreach ($pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN) as $table) {
    $check = $pdo->query('CHECK TABLE `' . $table . '`')->fetchAll();
    ok(end($check)['Msg_text'] === 'OK', 'Intégrité de la table ' . $table);
}
file_put_contents(__DIR__ . '/.audit-result.json', json_encode(['controles' => $nombre, 'pages' => $visites, 'date' => date(DATE_ATOM)], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo $nombre . ' contrôles réussis sur la base jetable ' . $etat['database'] . PHP_EOL;
