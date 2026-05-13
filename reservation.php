<?php
include 'includes/header.php';

if (!isset($_SESSION['client'])) {
    header("Location: login.php");
    exit();
}

$id_client = $_SESSION['client'];

// 🔥 Récupérer les essais
$stmt = $pdo->prepare("
    SELECT essai.*, voiture.modele, marque.nom_marque
    FROM essai
    JOIN voiture ON essai.id_voiture = voiture.id_voiture
    JOIN marque ON voiture.id_marque = marque.id_marque
    WHERE essai.id_client = ?
	    ORDER BY date_essai DESC, heure_essai DESC
");
$stmt->execute([$id_client]);
$essais = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mes réservations</title>
<style>
body {
    background: #111;
    color: white;
    font-family: Arial;
}

 .container {
    max-width: 900px;
    margin: 50px auto;
}

 h1 {
    text-align: center;
    margin-bottom: 40px;
}

 .card {
    background: #1c1c1c;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
}

 .status {
    font-weight: bold;
}

 .en-attente { color: orange; }
 .valide { color: green; }
 .refuse { color: red; }
</style>

</head>

<body>

<div class="container">

<h1>Mes réservations</h1>

<?php if (count($essais) == 0) { ?>
    <p style="text-align:center;">Aucune réservation pour le moment.</p>
<?php } ?>

<?php foreach ($essais as $e) { ?>

<div class="card">

    <h3><?php echo $e['nom_marque'] . " " . $e['modele']; ?></h3>

	    <p>Date : <?php echo $e['date_essai']; ?></p>
	    <p>Heure : <?php echo $e['heure_essai'] ? substr($e['heure_essai'], 0, 5) : 'Non renseignee'; ?></p>

    <p class="status <?php echo str_replace(' ', '-', strtolower($e['statut'])); ?>">
        Statut : <?php echo $e['statut']; ?>
    </p>

</div>

<?php } ?>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
