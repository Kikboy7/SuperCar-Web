<?php
/**
 * services.php - Page des services proposes par SuperCar.
 * Les services sont recuperes dynamiquement depuis la table `services`.
 */
$pageTitle = "Services";
include 'includes/header.php';

$services = $pdo->query("SELECT * FROM services ORDER BY id_services")->fetchAll();
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
        <div class="service-grid">
            <?php foreach ($services as $index => $service) { ?>
                <article class="service-card">
                    <div class="service-num"><?php echo $index + 1; ?></div>
                    <h3><?php echo e($service['nom_services']); ?></h3>
                    <p><?php echo e($service['description_services']); ?></p>
                    <span class="service-price">
                        <?php echo $service['prix_services'] > 0
                            ? 'Rs ' . number_format($service['prix_services'], 0, ',', ' ')
                            : 'Service gratuit'; ?>
                    </span>
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