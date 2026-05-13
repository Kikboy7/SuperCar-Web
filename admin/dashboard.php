<?php
include 'includes/auth.php';

$pageTitle = "Tableau de bord";

// On compte les donnees principales pour donner une vue rapide a l'administrateur.
$totalVoitures = $pdo->query("SELECT COUNT(*) FROM voiture")->fetchColumn();
$totalEssais = $pdo->query("SELECT COUNT(*) FROM essai")->fetchColumn();
$totalMessages = $pdo->query("SELECT COUNT(*) FROM message")->fetchColumn();
$totalServices = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();

include 'includes/header.php';
?>

<div class="grid">
    <div class="card">
        <h3>Voitures</h3>
        <p><?php echo $totalVoitures; ?> modele(s)</p>
        <a href="voitures.php" class="btn">Gerer</a>
    </div>

    <div class="card">
        <h3>Demandes d'essai</h3>
        <p><?php echo $totalEssais; ?> demande(s)</p>
        <a href="essais.php" class="btn">Voir</a>
    </div>

    <div class="card">
        <h3>Messages</h3>
        <p><?php echo $totalMessages; ?> message(s)</p>
        <a href="messages.php" class="btn">Consulter</a>
    </div>

    <div class="card">
        <h3>Services</h3>
        <p><?php echo $totalServices; ?> service(s)</p>
        <a href="services.php" class="btn">Gerer</a>
    </div>
</div>

<div class="card">
    <h2>Bienvenue dans l'administration</h2>
    <p>
        Cette partie permet de gerer les contenus importants du site SuperCar :
        voitures, demandes d'essai, messages clients et services.
    </p>
</div>

    </main>
</div>

</body>
</html>
