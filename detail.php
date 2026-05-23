<?php
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_GET['id'])) {
    die("Voiture non trouvée");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT voiture.*, marque.nom_marque 
    FROM voiture
    JOIN marque ON voiture.id_marque = marque.id_marque
    WHERE id_voiture = ?
");
$stmt->execute([$id]);
$car = $stmt->fetch();

if (!$car) {
    die("Voiture non trouvée");
}

$stmt = $pdo->prepare("SELECT * FROM voiture_image WHERE id_voiture = ?");
$stmt->execute([$id]);
$images = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title><?php echo $car['nom_marque'] . " " . $car['modele']; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #0f0f0f;
    color: white;
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
}


 .detail-container {
    max-width: 1300px;
    margin: 60px auto;
    padding: 20px;
}


 .detail-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 50px;
}


 .main-img {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: 15px;
    transition: 0.4s;
}

 .thumbnails {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

 .thumb {
    width: 90px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    opacity: 0.6;
    transition: 0.3s;
}

 .thumb:hover {
    opacity: 1;
}


 .title {
    font-size: 40px;
    font-weight: bold;
}

 .price {
    font-size: 28px;
    color: #f39c12;
    margin: 15px 0;
}

 .desc {
    color: #bbb;
    margin-bottom: 25px;
    line-height: 1.6;
}


 .specs {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 30px;
}

 .spec {
    background: #1c1c1c;
    padding: 15px;
    border-radius: 10px;
}

 .spec span {
    display: block;
    color: #888;
    font-size: 14px;
}

 .spec strong {
    font-size: 16px;
}


 .actions {
    display: flex;
    gap: 15px;
}

 .btn-main {
    background: #f39c12;
    padding: 12px 25px;
    border-radius: 30px;
    color: black;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}

 .btn-main:hover {
    background: white;
}

 .btn-secondary {
    border: 1px solid #555;
    padding: 12px 25px;
    border-radius: 30px;
    color: white;
    text-decoration: none;
}

 .btn-secondary:hover {
    background: #222;
}


 .extra {
    margin-top: 60px;
}

 .extra h3 {
    margin-bottom: 20px;
}

 .features {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

 .feature {
    background: #1c1c1c;
    padding: 15px 20px;
    border-radius: 10px;
}


@media(max-width: 900px) {
.detail-grid {
        grid-template-columns: 1fr;
    }
}
</style>

</head>

<body>

<section class="detail-container">

<div class="detail-grid">

    <!-- IMAGES -->
    <div>
        <img src="images/<?php echo $images[0]['url'] ?? $car['image']; ?>" class="main-img">

        <div class="thumbnails">
            <?php foreach ($images as $img) { ?>
                <img src="images/<?php echo $img['url']; ?>" class="thumb">
            <?php } ?>
        </div>
    </div>

    <!-- INFOS -->
    <div>

        <h1 class="title">
            <?php echo $car['nom_marque'] . " " . $car['modele']; ?>
        </h1>

        <p class="price">
            Rs <?php echo number_format($car['prix'], 0, ',', ' '); ?>
        </p>

        <p class="desc">
            <?php echo $car['description'] ?? "Une voiture haut de gamme combinant performance, confort et design exceptionnel. Idéale pour les passionnés d’automobile à la recherche d’une expérience unique."; ?>
        </p>

        <!-- SPECS -->
        <div class="specs">

            <div class="spec">
                <span>Puissance</span>
                <strong><?php echo $car['puissance'] ?? '450'; ?> ch</strong>
            </div>

            <div class="spec">
                <span>Carburant</span>
                <strong><?php echo $car['carburant'] ?? 'Essence'; ?></strong>
            </div>

            <div class="spec">
                <span>Boîte</span>
                <strong><?php echo $car['boite'] ?? 'Automatique'; ?></strong>
            </div>

            <div class="spec">
                <span>Année</span>
                <strong><?php echo $car['annee'] ?? '2023'; ?></strong>
            </div>

        </div>

        <!-- ACTIONS -->
        <div class="actions">
	            <a href="demande_essai.php?id=<?php echo $car['id_voiture']; ?>" class="btn-main">
                Réserver un essai
            </a>

            <a href="voitures.php" class="btn-secondary">
                Retour
            </a>
        </div>

    </div>

</div>

<!-- SECTION BONUS -->
<div class="extra">

    <h3>Équipements & Options</h3>

    <div class="features">
        <div class="feature">GPS intégré</div>
        <div class="feature">Sièges chauffants</div>
        <div class="feature">Caméra 360°</div>
        <div class="feature">Mode sport</div>
    </div>

</div>

</section>

<script>
document.querySelectorAll('.thumb').forEach(img => {
    img.addEventListener('click', function () {
        const main = document.querySelector('.main-img');
        main.style.opacity = 0;
        setTimeout(() => {
            main.src = this.src;
            main.style.opacity = 1;
        }, 150);
    });
});
</script>

<?php include 'includes/footer.php'; ?>

</body>
</html>
