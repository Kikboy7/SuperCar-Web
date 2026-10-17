<?php
/**
 * service.php - Fiche detaillee d'un service.
 * Affiche la photo, la presentation complete, le prix et le contact
 * adapte au service (essai -> demande d'essai, sinon -> contact).
 */
require_once __DIR__ . '/includes/init.php';

// Il faut obligatoirement un id de service valide dans l'URL.
if (!ctype_digit(saisie('id', $_GET))) {
    header("Location: services.php");
    exit();
}

$id = (int) $_GET['id'];

// Informations du service.
$stmt = $pdo->prepare("SELECT * FROM services WHERE id_services = ?");
$stmt->execute([$id]);
$service = $stmt->fetch();

// Si le service n'existe pas, on revient a la liste.
if (!$service) {
    header("Location: services.php");
    exit();
}

$pageTitle = $service['nom_services'];

// Les autres services (sans le service actuel).
$stmt = $pdo->prepare("SELECT * FROM services WHERE id_services != ? ORDER BY id_services LIMIT 3");
$stmt->execute([$id]);
$autres = $stmt->fetchAll();

// L'essai se reserve, les autres services se demandent via le contact.
$estEssai = stripos($service['nom_services'], 'essai') !== false;
include 'includes/header.php';
?>

<section class="detail-page">
    <div class="container">

        <a href="services.php" class="btn btn-ghost btn-sm mb-2">&larr; Retour aux services</a>

        <div class="detail-grid">

            <!-- ============ PHOTO ============ -->
            <div class="fade-up">
                <div class="gallery-main" style="aspect-ratio: 4/3;">
                    <?php if (!empty($service['image_services'])) { ?>
                        <img src="<?php echo e(image_versionnee($service['image_services'])); ?>" alt="<?php echo e($service['nom_services']); ?>">
                    <?php } ?>
                </div>
            </div>

            <!-- ============ INFORMATIONS ============ -->
            <div class="fade-up delay-1">
                <div class="detail-brand">Service SuperCar</div>
                <h1 class="detail-title"><?php echo e($service['nom_services']); ?></h1>

                <div class="detail-price">
                    <?php echo $service['prix_services'] > 0
                        ? 'Rs ' . number_format($service['prix_services'], 0, ',', ' ')
                        : 'Gratuit'; ?>
                </div>

                <p class="detail-desc"><?php echo e($service['description_services']); ?></p>

                <div class="detail-actions">
                    <?php if ($estEssai) { ?>
                        <a href="demande_essai.php" class="btn btn-main">R&eacute;server mon essai</a>
                    <?php } else { ?>
                        <a href="contact.php" class="btn btn-main">Contacter un conseiller</a>
                    <?php } ?>
                    <a href="voitures.php" class="btn btn-outline">Voir les voitures</a>
                </div>
            </div>

        </div>

        <!-- ============ PRESENTATION COMPLETE ============ -->
        <?php if (!empty($service['details_services'])) { ?>
            <div class="form-card wide mt-4">
                <h2 class="form-title" style="text-align:left;">En d&eacute;tail</h2>
                <p class="detail-desc" style="margin-bottom:0;"><?php echo nl2br(e($service['details_services'])); ?></p>
            </div>
        <?php } ?>

        <!-- ============ CE SERVICE COMPREND ============ -->
        <?php
        $points = array_filter(array_map('trim', explode("\n", $service['points_services'] ?? '')));
        if (count($points) > 0) { ?>
            <div class="form-card wide mt-4">
                <h2 class="form-title" style="text-align:left;">Ce service comprend</h2>
                <ul class="checklist">
                    <?php foreach ($points as $point) { ?>
                        <li><?php echo e($point); ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <!-- ============ AUTRES SERVICES ============ -->
        <?php if (count($autres) > 0) { ?>
            <div class="mt-4">
                <div class="section-head">
                    <span class="section-tag">SuperCar</span>
                    <h2 class="section-title">Nos autres services</h2>
                </div>

                <div class="service-grid service-grid--3">
                    <?php foreach ($autres as $autre) { ?>
                        <article class="service-card">
                            <div class="service-num">&rarr;</div>
                            <h3><?php echo e($autre['nom_services']); ?></h3>
                            <p><?php echo e($autre['description_services']); ?></p>
                            <span class="service-price">
                                <?php echo $autre['prix_services'] > 0
                                    ? 'Rs ' . number_format($autre['prix_services'], 0, ',', ' ')
                                    : 'Service gratuit'; ?>
                            </span>
                            <div class="mt-2">
                                <a href="service.php?id=<?php echo $autre['id_services']; ?>" class="btn btn-outline btn-sm">En savoir plus</a>
                            </div>
                        </article>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
