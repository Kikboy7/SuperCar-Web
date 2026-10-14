<?php
/**
 * services.php - Page des services proposes par SuperCar.
 * Les services sont recuperes dynamiquement depuis la table `services`
 * et affiches en grandes cartes illustrees (2 par ligne).
 */
$pageTitle = "Services";
include 'includes/header.php';

$services = $pdo->query("
    SELECT * FROM services
    ORDER BY FIELD(
        nom_services,
        'Conseil d''achat',
        'Essai personnalisé',
        'Démarches administratives',
        'Livraison à domicile',
        'Reprise de votre ancien véhicule',
        'Financement sur mesure',
        'Garantie 12 mois',
        'Detailing & entretien'
    ), id_services
")->fetchAll();
?>

<section class="page-hero">
    <div class="container">
        <span class="section-tag">Services SuperCar</span>
        <h1>Un accompagnement simple avant, pendant et apr&egrave;s l'achat</h1>
        <p>SuperCar aide les clients &agrave; choisir leur v&eacute;hicule, organiser un essai et pr&eacute;parer les prochaines &eacute;tapes avec un conseiller.</p>
    </div>
</section>

<div class="container section">
    <?php if (count($services) > 0) { ?>
        <div class="service-show-grid">
            <?php foreach ($services as $index => $service) { ?>
                <article class="service-show-card">
                    <a href="service.php?id=<?php echo $service['id_services']; ?>" class="thumb">
                        <?php if (!empty($service['image_services'])) { ?>
                            <img src="<?php echo e(image_versionnee($service['image_services'])); ?>" alt="<?php echo e($service['nom_services']); ?>" loading="lazy">
                        <?php } ?>
                        <span class="price-badge">
                            <?php echo $service['prix_services'] > 0
                                ? 'Rs ' . number_format($service['prix_services'], 0, ',', ' ')
                                : 'Gratuit'; ?>
                        </span>
                    </a>
                    <div class="body">
                        <span class="service-kicker">Service <?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></span>
                        <h3>
                            <a href="service.php?id=<?php echo $service['id_services']; ?>">
                                <?php echo e($service['nom_services']); ?>
                            </a>
                        </h3>
                        <p><?php echo e($service['description_services']); ?></p>
                        <a href="service.php?id=<?php echo $service['id_services']; ?>" class="service-more">En savoir plus &rarr;</a>
                    </div>
                </article>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="empty-state">
            <h3>Aucun service disponible</h3>
            <p>De nouveaux services arrivent bient&ocirc;t.</p>
        </div>
    <?php } ?>

    <div class="cta-banner" style="margin-top:60px;">
        <div class="container">
            <h2>Envie de découvrir nos voitures&nbsp;?</h2>
            <div class="cta-actions">
                <a href="voitures.php" class="btn btn-main">Voir les voitures</a>
                <a href="contact.php" class="btn btn-outline">Nous contacter</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
