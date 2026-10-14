<?php
/**
 * functions.php
 * Fonctions utilitaires partagees par tout le site.
 * - e() : echappe les donnees avant de les afficher (anti XSS).
 * - Les 3 fonctions csrf_* : protection contre les fausses requetes (CSRF).
 */

if (!defined('SUPERCAR_FUNCTIONS_LOADED')) {
    define('SUPERCAR_FUNCTIONS_LOADED', true);
} else {
    return;
}

/**
 * e() : raccourci pour htmlspecialchars().
 * Convertit les caracteres speciaux (<, >, ", ', &) en entites HTML.
 * On utilise systematiquement e() quand on affiche une donnee venant de la base
 * afin d'empecher l'execution de code JavaScript injecte (faille XSS).
 */
function e($valeur) {
    return htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8');
}

/** Sélection éditoriale de l’accueil : au maximum six marques distinctes. */
function voitures_phares(PDO $pdo) {
    // Une voiture emblématique par marque. Si elle n’est plus au catalogue,
    // retenir le modèle le plus puissant, puis le plus récent de cette marque.
    return $pdo->query("
        SELECT v.*, m.nom_marque
        FROM voiture v
        JOIN marque m ON m.id_marque = v.id_marque
        WHERE v.id_voiture = (
            SELECT v2.id_voiture
            FROM voiture v2
            JOIN marque m2 ON m2.id_marque = v2.id_marque
            WHERE v2.id_marque = m.id_marque
            ORDER BY
                (v2.modele = CASE m2.nom_marque
                    WHEN 'BMW' THEN 'M8 Competition'
                    WHEN 'Audi' THEN 'RS6 Avant'
                    WHEN 'Mercedes' THEN 'AMG GT'
                    WHEN 'Porsche' THEN '911 Turbo S'
                    WHEN 'Ferrari' THEN 'F8 Tributo'
                    WHEN 'Land Rover' THEN 'Range Rover Sport'
                    ELSE ''
                END) DESC,
                COALESCE(v2.puissance, 0) DESC,
                COALESCE(v2.annee, 0) DESC,
                v2.id_voiture ASC
            LIMIT 1
        )
        ORDER BY CASE m.nom_marque
            WHEN 'BMW' THEN 1
            WHEN 'Audi' THEN 2
            WHEN 'Mercedes' THEN 3
            WHEN 'Porsche' THEN 4
            WHEN 'Ferrari' THEN 5
            WHEN 'Land Rover' THEN 6
            ELSE 7
        END, m.nom_marque, m.id_marque
        LIMIT 6
    ")->fetchAll();
}

/** Recharge les photos remplacées, même si l’ancienne version est en cache. */
function image_versionnee($nom) {
    $chemin = __DIR__ . '/../images/' . $nom;
    return 'images/' . $nom . (is_file($chemin) ? '?v=' . filemtime($chemin) : '');
}

/** Même dessin vectoriel dans les trois palettes : clair, sombre et admin. */
function supercar_logo($compact = false) {
    static $logos = [];
    $fichier = $compact ? 'supercar-mark.svg' : 'supercar-logo.svg';
    if (!isset($logos[$fichier])) {
        $logos[$fichier] = file_get_contents(__DIR__ . '/../images/' . $fichier);
    }
    return $logos[$fichier];
}

/**
 * csrf_token() : cree (ou recupere) le jeton de securite de la session.
 * Un jeton est un grand nombre aleatoire etre connu uniquement par une seule session,
 * ce qui permet de verifier qu'un formulaire vient bien du navigateur du client.
 */
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * csrf_field() : renvoie un champ caché HTML contenant le jeton.
 * A insérer dans chaque formulaire (mode POST) du site.
 */
function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

/**
 * csrf_verify() : verifie que le formulaire recu contient le bon jeton.
 * Renvoie true si le jeton est valide, false sinon.
 */
function csrf_verify() {
    $envoye = $_POST['csrf'] ?? '';
    return hash_equals(csrf_token(), $envoye);
}
