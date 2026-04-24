<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

$client = null;

if (isset($_SESSION['client'])) {
    $stmt = $pdo->prepare("SELECT nom FROM client WHERE id_client = ?");
    $stmt->execute([$_SESSION['client']]);
    $client = $stmt->fetch();
}
?>

<style>
/* 🔥 NAVBAR CUSTOM (écrase Bootstrap proprement) */
.custom-navbar {
    position: sticky;
    top: 0;
    z-index: 1000;

    background: rgba(0,0,0,0.9) !important;
    backdrop-filter: blur(10px);

    padding: 15px 0;
}

/* CONTENEUR CENTRÉ */
.custom-container {
    max-width: 1200px;
    margin: auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
}

/* LOGO */
.logo img {
    height: 45px;
}

/* MENU */
.nav-center {
    display: flex;
    gap: 30px;
}

/* LINKS */
.nav-center a,
.nav-right a,
.nav-right span {
    color: #ccc !important;
    text-decoration: none;
    position: relative;
    transition: 0.3s;
}

/* HOVER */
.nav-center a::after,
.nav-right a::after {
    content: "";
    position: absolute;
    width: 0%;
    height: 2px;
    background: #f39c12;
    left: 0;
    bottom: -5px;
    transition: 0.3s;
}

.nav-center a:hover::after,
.nav-right a:hover::after {
    width: 100%;
}

.nav-center a:hover,
.nav-right a:hover {
    color: white !important;
}

/* USER */
.nav-right {
    display: flex;
    gap: 20px;
    align-items: center;
}

.user {
    color: #f39c12 !important;
}
</style>

<nav class="custom-navbar">

    <div class="custom-container">

        <!-- LOGO -->
        <a href="index.php" class="logo">
            <img src="images/logo.png">
        </a>

        <!-- MENU -->
        <div class="nav-center">
            <a href="index.php">Accueil</a>
            <a href="voitures.php">Voitures</a>
            <a href="essai.php">Essai</a>
            <a href="services.php">Services</a>
            <a href="contact.php">Contact</a>
        </div>

        <!-- USER -->
        <div class="nav-right">
            <?php if ($client) { ?>
                <span class="user">👤 <?php echo $client['nom']; ?></span>
                <a href="logout.php">Logout</a>
            <?php } else { ?>
                <a href="login.php">Login</a>
            <?php } ?>
        </div>

    </div>

</nav>