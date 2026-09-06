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