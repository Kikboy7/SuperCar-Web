<?php
/**
 * detail.php - Fiche detaillee d'une voiture.
 * Affiche la galerie d'images (voiture_image), les informations et les
 * caracteristiques techniques de la voiture choisie.
 */
include 'includes/header.php';

// Il faut obligatoirement un id de voiture valide dans l'URL.
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: voitures.php");
    exit();
}

$id = (int) $_GET['id'];

// Informations de la voiture + nom de sa marque.
$stmt = $pdo->prepare("
    SELECT voiture.*, marque.nom_marque
    FROM voiture
    JOIN marque ON voiture.id_marque = marque.id_marque
    WHERE voiture.id_voiture = ?
");
$stmt->execute([$id]);
$car = $stmt->fetch();

// Si la voiture n'existe pas, on revient au catalogue.
if (!$car) {
    header("Location: voitures.php");
    exit();
}

$pageTitle = $car['nom_marque'] . ' ' . $car['modele'];

// Les images supplementaires de la voiture.
$stmt = $pdo->prepare("SELECT url FROM voiture_image WHERE id_voiture = ? ORDER BY id_image");
$stmt->execute([$id]);
$images = $stmt->fetchAll();

// D'autres modeles de la meme marque (sans la voiture actuelle).
$stmt = $pdo->prepare("
    SELECT id_voiture, modele, image, prix
    FROM voiture
    WHERE id_marque = ? AND id_voiture != ?
    LIMIT 3
");
$stmt->execute([$car['id_marque'], $id]);
$modeles_proches = $stmt->fetchAll();
?>

<section class="detail-page">
    <div class="container">

        <a href="voitures.php" class="btn btn-ghost btn-sm mb-2">&larr; Retour au catalogue</a>

        <div class="detail-grid">

            <!-- ============ GALERIE ============ -->
            <div class="fade-up">
                <div class="gallery-main">
                    <img src="images/<?php echo e($images[0]['url'] ?? $car['image']); ?>" alt="<?php echo e($car['nom_marque'] . ' ' . $car['modele']); ?>" id="mainImg">
                </div>

                <?php if (count($images) > 0) { ?>
                    <div class="gallery-thumbs">
                        <?php foreach ($images as $index => $img) { ?>
                            <img src="images/<?php echo e($img['url']); ?>"
                                 alt="Image <?php echo $index + 1; ?>"
                                 class="<?php echo $index === 0 ? 'active' : ''; ?>"
                                 data-src="images/<?php echo e($img['url']); ?>">
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

            <!-- ============ INFORMATIONS ============ -->
            <div class="fade-up delay-1">
                <div class="detail-brand"><?php echo e($car['nom_marque']); ?></div>
                <h1 class="detail-title"><?php echo e($car['modele']); ?></h1>

                <div class="detail-price">Rs <?php echo number_format($car['prix'], 0, ',', ' '); ?></div>

                <p class="detail-desc">
                    <?php echo e($car['description'] ?? 'Une voiture haut de gamme combinant performance, confort et design exceptionnel.'); ?>
                </p>

                <div class="specs-grid">
                    <div class="spec-box">
                        <span>Puissance</span>
                        <strong><?php echo (int) ($car['puissance'] ?? 0); ?> ch</strong>
                    </div>
                    <div class="spec-box">
                        <span>Carburant</span>
                        <strong><?php echo e($car['carburant'] ?? 'Non renseign&eacute;'); ?></strong>
                    </div>
                    <div class="spec-box">
                        <span>Bo&icirc;te de vitesses</span>
                        <strong><?php echo e($car['boite'] ?? 'Non renseign&eacute;'); ?></strong>
                    </div>
                    <div class="spec-box">
                        <span>Ann&eacute;e</span>
                        <strong><?php echo (int) ($car['annee'] ?? 0); ?></strong>
                    </div>
                </div>

                <div class="detail-actions">
                    <a href="demande_essai.php?id=<?php echo $car['id_voiture']; ?>" class="btn btn-main">R&eacute;server un essai</a>
                    <a href="contact.php" class="btn btn-outline">Poser une question</a>
                </div>
            </div>

        </div>

        <!-- ============ AUTRES MODELES ============ -->
        <?php if (count($modeles_proches) > 0) { ?>
            <div class="mt-4">
                <div class="section-head">
                    <span class="section-tag"><?php echo e($car['nom_marque']); ?></span>
                    <h2 class="section-title">Autres mod&egrave;les de la m&ecirc;me marque</h2>
                </div>

                <div class="car-grid">
                    <?php foreach ($modeles_proches as $autre) { ?>
                        <article class="car-card">
                            <a href="detail.php?id=<?php echo $autre['id_voiture']; ?>" class="thumb">
                                <img src="images/<?php echo e($autre['image']); ?>" alt="<?php echo e($autre['modele']); ?>" loading="lazy">
                            </a>
                            <div class="body">
                                <h3><?php echo e($car['nom_marque'] . ' ' . $autre['modele']); ?></h3>
                                <span class="price">Rs <?php echo number_format($autre['prix'], 0, ',', ' '); ?></span>
                                <div class="actions">
                                    <a href="detail.php?id=<?php echo $autre['id_voiture']; ?>" class="btn btn-outline btn-sm">Voir</a>
                                </div>
                            </div>
                        </article>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

    </div>
</section>

<script>
// Galerie : cliquer sur une miniature remplace l'image principale.
document.querySelectorAll('.gallery-thumbs img').forEach(function (thumb) {
    thumb.addEventListener('click', function () {
        document.querySelectorAll('.gallery-thumbs img').forEach(function (t) { t.classList.remove('active'); });
        thumb.classList.add('active');
        var main = document.getElementById('mainImg');
        main.style.opacity = 0;
        setTimeout(function () {
            main.src = thumb.dataset.src;
            main.style.opacity = 1;
        }, 150);
    });
});
</script>

<?php include 'includes/footer.php'; ?>