<?php
/**
 * header.php
 * Partie haute de chaque page : session, connexion BDD, entete HTML et
 * barre de navigation responsive.
 *
 * Chaque page doit definir $pageTitle avant d'inclure ce fichier.
 */

// Session : permet de savoir si un client est connecte.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connexion a la base + fonctions utilitaires.
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

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

    <!-- Polices modernes (restent lues en local si internet indisponible) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap + feuille de style du site -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar">
    <div class="container navbar-inner">

        <!-- Logo -->
        <a href="index.php" class="logo">
            <img src="images/logo.png" alt="SuperCar">
        </a>

        <!-- Bouton menu mobile (hamburger) -->
        <button class="nav-toggle" id="navToggle" aria-label="Ouvrir le menu">
            <span></span>
        </button>

        <!-- Partie repliable sur mobile : menu + zone compte -->
        <div class="nav-collapse" id="navCollapse">

            <!-- Liens de navigation -->
            <ul class="nav-links">
                <li><a href="index.php" class="<?php echo $pageCourante == 'index.php' ? 'active' : ''; ?>">Accueil</a></li>
                <li><a href="voitures.php" class="<?php echo in_array($pageCourante, ['voitures.php', 'detail.php']) ? 'active' : ''; ?>">Voitures</a></li>
                <li><a href="demande_essai.php" class="<?php echo $pageCourante == 'demande_essai.php' ? 'active' : ''; ?>">Demander un essai</a></li>
                <li><a href="services.php" class="<?php echo $pageCourante == 'services.php' ? 'active' : ''; ?>">Services</a></li>
                <li><a href="contact.php" class="<?php echo $pageCourante == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
            </ul>

            <!-- Zone compte (a droite sur ecran large) -->
            <div class="nav-actions">
                <?php if ($client) { ?>
                    <a href="compte.php" class="nav-user">
                        &#128100; <?php echo e($client['nom']); ?>
                    </a>
                    <a href="logout.php" class="btn btn-outline btn-sm">D&eacute;connexion</a>
                <?php } else { ?>
                    <a href="compte.php" class="btn btn-main btn-sm">Mon compte</a>
                <?php } ?>
            </div>

        </div>

    </div>
</nav>

<main>