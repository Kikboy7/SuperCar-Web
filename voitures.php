<?php include 'includes/header.php'; 
include 'includes/db.php';?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SuperCar | Nos voitures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #0f0f0f;
            color: white;
            font-family: 'Segoe UI', sans-serif;
        }

        /* TITRE */
        .title {
            text-align: center;
            margin: 60px 0;
            font-size: 40px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        /* SECTION MARQUE */
        .brand-title {
            margin: 40px 0 20px;
            font-size: 28px;
            border-left: 5px solid #f39c12;
            padding-left: 10px;
        }

        /* CARD */
        .car-card {
            background: #1c1c1c;
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s;
            position: relative;
        }

        .car-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: 0.4s;
        }

        .car-card:hover img {
            transform: scale(1.1);
        }

        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 15px 30px rgba(0,0,0,0.6);
        }

        /* INFOS */
        .car-info {
            padding: 15px;
            text-align: center;
        }

        .car-info h4 {
            margin: 10px 0;
            font-weight: bold;
        }

        .price {
            color: #f39c12;
            font-size: 18px;
            margin-bottom: 10px;
        }

        /* BOUTON */
        .btn-detail {
            background: transparent;
            border: 1px solid #f39c12;
            color: #f39c12;
            padding: 6px 15px;
            border-radius: 20px;
            transition: 0.3s;
        }

        .btn-detail:hover {
            background: #f39c12;
            color: black;
        }

        /* GRID */
        .row {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1 class="title">NOS VOITURES</h1>

<?php
// récupérer marques
$marques = $pdo->query("SELECT * FROM marque");

while ($marque = $marques->fetch()) {

    echo "<h2 class='brand-title'>" . $marque['nom_marque'] . "</h2>";
    echo "<div class='row'>";

    $stmt = $pdo->prepare("
        SELECT * FROM voiture 
        WHERE id_marque = ?
    ");
    $stmt->execute([$marque['id_marque']]);

    while ($car = $stmt->fetch()) {
?>

        <div class="col-md-4 mb-4">
            <div class="car-card">

                <img src="images/<?php echo $car['image']; ?>">

                <div class="car-info">
                    <h4><?php echo $marque['nom_marque'] . " " . $car['modele']; ?></h4>

                    <p class="price">
                        Rs <?php echo number_format($car['prix'], 0, ',', ' '); ?>
                    </p>

                    <a href="detail.php?id=<?php echo $car['id_voiture']; ?>" class="btn-detail">
                        Voir détails
                    </a>
                </div>

            </div>
        </div>

<?php
    }

    echo "</div>";
}
?>

</div>

</body>
</html>