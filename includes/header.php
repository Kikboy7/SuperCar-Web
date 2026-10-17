<?php
/**
 * header.php
 * Partie haute de chaque page : session, connexion BDD, entete HTML et
 * barre de navigation responsive.
 *
 * Chaque page doit definir $pageTitle avant d'inclure ce fichier.
 */

// Session : permet de savoir si un client est connecte.
require_once __DIR__ . '/init.php';

// Connexion a la base + fonctions utilitaires.
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/contact_config.php';
$coordonneesSite = $coordonneesSite ?? contact_charger($pdo);

// Titre de la page (par defaut s'il n'est pas defini par la page).
$pageTitle = $pageTitle ?? 'SuperCar';

// Page actuelle (pour mettre en surbrillance le lien du menu).
$pageCourante = basename($_SERVER['PHP_SELF']);

// On recupere les infos du client connecte pour la barre de navigation.
$client = null;
if (isset($_SESSION['client'])) {
    $stmt = $pdo->prepare("SELECT nom, email FROM client WHERE id_client = ?");
    $stmt->execute([$_SESSION['client']]);
    $client = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> | SuperCar</title>

    <!-- Polices distantes ; les polices système servent de repli sans Internet. -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap + feuille de style du site -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css?v=<?php echo filemtime(__DIR__ . '/../style.css'); ?>" rel="stylesheet">

    <script>
    document.documentElement.classList.add('js');
    // Appliquer le theme memorise avant l'affichage de la page.
    try { if (localStorage.getItem('supercar-theme') === 'light') {
        document.documentElement.setAttribute('data-theme', 'light');
    } } catch (erreur) { /* Le stockage peut être désactivé dans le navigateur. */ }
    </script>
</head>
<body id="haut">
<a href="#contenu" class="skip-link">Aller au contenu</a>

<nav class="navbar" aria-label="Navigation principale">
    <div class="header-detail">
        <div class="container header-detail-inner">
            <span><?php echo e($coordonneesSite['adresse'] . ' · ' . $coordonneesSite['ville'] . ', ' . $coordonneesSite['pays']); ?></span>
            <span>Véhicules premium <span aria-hidden="true">·</span> Essais sur réservation</span>
        </div>
    </div>
    <div class="container navbar-inner">

        <!-- Logo -->
        <a href="index.php" class="logo" aria-label="SuperCar — Accueil">
            <?php echo supercar_logo(); ?>
        </a>

        <!-- Bouton menu mobile (hamburger) -->
        <button type="button" class="nav-toggle" id="navToggle" aria-label="Ouvrir le menu" aria-controls="navCollapse" aria-expanded="false">
            <span></span>
        </button>

        <!-- Partie repliable sur mobile : menu + zone compte -->
        <div class="nav-collapse" id="navCollapse">

            <!-- Liens de navigation -->
            <ul class="nav-links">
                <li><a href="index.php" class="<?php echo $pageCourante == 'index.php' ? 'active' : ''; ?>">Accueil</a></li>
                <li><a href="voitures.php" class="<?php echo in_array($pageCourante, ['voitures.php', 'detail.php']) ? 'active' : ''; ?>">Voitures</a></li>
                <li><a href="demande_essai.php" class="<?php echo in_array($pageCourante, ['demande_essai.php', 'presentation.php']) ? 'active' : ''; ?>">Demande d’essai</a></li>
                <li><a href="services.php" class="<?php echo $pageCourante == 'services.php' ? 'active' : ''; ?>">Services</a></li>
                <li><a href="contact.php" class="<?php echo $pageCourante == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
            </ul>

            <!-- Zone compte (a droite sur ecran large) -->
            <div class="nav-actions">
                <button type="button" class="theme-toggle" id="themeToggle" aria-label="Activer le thème clair" title="Activer le thème clair">
                    <span class="theme-icon theme-icon-light" aria-hidden="true">&#9788;</span>
                    <span class="theme-icon theme-icon-dark" aria-hidden="true">&#9790;</span>
                </button>

                <?php if ($client) { ?>
                    <a href="compte.php" class="nav-user">
                        &#128100; <?php echo e($client['nom']); ?>
                    </a>
                    <form method="POST" action="logout.php" class="logout-form"><?php echo csrf_field(); ?><button type="submit" class="btn btn-outline btn-sm">Déconnexion</button></form>
                <?php } else { ?>
                    <a href="compte.php" class="btn btn-main btn-sm">Mon compte</a>
                <?php } ?>
            </div>

        </div>

    </div>
</nav>

<main id="contenu">
