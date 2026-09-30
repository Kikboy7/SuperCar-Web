<?php
/**
 * index.php - Page d'accueil (front-office).
 * Tous les contenus (nombre de voitures, voitures a la une, marques, services)
 * sont recuperes depuis la base de donnees : la page est donc dynamique.
 */
$pageTitle = "Accueil";
include 'includes/header.php';

// Statistiques globales (affichees dans le hero).
$totalVoitures = $pdo->query("SELECT COUNT(*) FROM voiture")->fetchColumn();
$totalMarques  = $pdo->query("SELECT COUNT(*) FROM marque")->fetchColumn();

// Les 6 dernieres voitures ajoutees, avec le nom de leur marque.
$stmt = $pdo->query("
    SELECT v.*, m.nom_marque
    FROM voiture v
    JOIN marque m ON v.id_marque = m.id_marque
    ORDER BY v.id_voiture DESC
    LIMIT 6
");
$voitures = $stmt->fetchAll();

// Les marques disponibles (avec une image representative).
$stmt = $pdo->query("
    SELECT m.id_marque, m.nom_marque, COUNT(v.id_voiture) AS nb_voitures,
           (SELECT v2.image FROM voiture v2 WHERE v2.id_marque = m.id_marque ORDER BY v2.id_voiture LIMIT 1) AS image
    FROM marque m
    LEFT JOIN voiture v ON v.id_marque = m.id_marque
    GROUP BY m.id_marque, m.nom_marque
");
$marques = $stmt->fetchAll();

// Afficher seulement les 3 premiers services sur la page d'accueil.
$services = $pdo->query("
    SELECT * FROM services
    ORDER BY FIELD(
        nom_services,
        'Conseil d''achat',
        'Essai personnalisé',
        'Démarches administratives',
        'Livraison à domicile'
    ), id_services
    LIMIT 3
")->fetchAll();

// Textes modifiables depuis la partie administration.
$contenuAccueil = $pdo->query("SELECT * FROM contenu_accueil WHERE id_contenu = 1")->fetch();
?>

<!-- ============ HERO + CARROUSEL ============ -->
<section class="hero">

    <!-- Carrousel d'images de fond -->
    <div class="hero-slider">
        <img src="images/hero-mercedes.webp" alt="" class="hero-slide hero-slide-mercedes is-active" data-slide="0" fetchpriority="high">
        <img src="images/hero-audi.webp?v=3" alt="" class="hero-slide hero-slide-audi" data-slide="1">
        <img src="images/hero-bmw.webp?v=3" alt="" class="hero-slide hero-slide-bmw" data-slide="2">
        <img src="images/hero-porsche.webp?v=3" alt="" class="hero-slide hero-slide-porsche" data-slide="3">
        <img src="images/hero-ferrari.webp?v=2" alt="" class="hero-slide hero-slide-ferrari" data-slide="4">
        <img src="images/hero-landrover.webp?v=2" alt="" class="hero-slide hero-slide-landrover" data-slide="5">
    </div>

    <div class="container">
        <div class="hero-content fade-up">
            <span class="hero-tag"><?php echo e($contenuAccueil['hero_surtitre']); ?></span>
            <h1><?php echo e($contenuAccueil['hero_titre_ligne1']); ?><br><span><?php echo e($contenuAccueil['hero_titre_ligne2']); ?></span></h1>
            <p><?php echo e($contenuAccueil['hero_description']); ?></p>

            <div class="hero-actions">
                <a href="voitures.php" class="btn btn-main">Voir le catalogue</a>
                <a href="demande_essai.php" class="btn btn-outline">R&eacute;server un essai</a>
            </div>

            <div class="hero-stats">
                <div class="stat">
                    <strong><?php echo (int) $totalVoitures; ?>+</strong>
                    <span>Mod&egrave;les premium</span>
                </div>
                <div class="stat">
                    <strong><?php echo (int) $totalMarques; ?></strong>
                    <span>Marques internationales</span>
                </div>
                <div class="stat">
                    <strong>2009</strong>
                    <span>Ann&eacute;e de cr&eacute;ation</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ VOITURES A LA UNE ============ -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-tag">Notre s&eacute;lection</span>
            <h2 class="section-title">Nos mod&egrave;les phares</h2>
            <p class="section-sub">Les derni&egrave;res voitures arriv&eacute;es dans notre catalogue.</p>
        </div>

        <div class="car-grid">
            <?php foreach ($voitures as $car) { ?>
                <article class="car-card">
                    <a href="detail.php?id=<?php echo $car['id_voiture']; ?>" class="thumb">
                        <span class="brand-chip"><?php echo e($car['nom_marque']); ?></span>
                        <img src="images/<?php echo e($car['image']); ?>" alt="<?php echo e($car['nom_marque'] . ' ' . $car['modele']); ?>" loading="lazy">
                    </a>
                    <div class="body">
                        <h3><?php echo e($car['nom_marque'] . ' ' . $car['modele']); ?></h3>
                        <span class="price">Rs <?php echo number_format($car['prix'], 0, ',', ' '); ?></span>

                        <div class="specs">
                            <?php if ($car['puissance']) { ?><span class="spec-chip"><?php echo (int) $car['puissance']; ?> ch</span><?php } ?>
                            <?php if ($car['carburant']) { ?><span class="spec-chip"><?php echo e($car['carburant']); ?></span><?php } ?>
                            <?php if ($car['annee']) { ?><span class="spec-chip"><?php echo (int) $car['annee']; ?></span><?php } ?>
                        </div>

                        <div class="actions">
                            <a href="detail.php?id=<?php echo $car['id_voiture']; ?>" class="btn btn-outline btn-sm">Voir d&eacute;tails</a>
                            <a href="demande_essai.php?id=<?php echo $car['id_voiture']; ?>" class="btn btn-main btn-sm">R&eacute;server</a>
                        </div>
                    </div>
                </article>
            <?php } ?>
        </div>

        <div class="text-center mt-4">
            <a href="voitures.php" class="btn btn-outline">Voir toute la collection</a>
        </div>
    </div>
</section>

<!-- ============ MARQUES ============ -->
<section class="section" style="background: var(--noir-2); border-top: 1px solid var(--bordure); border-bottom: 1px solid var(--bordure);">
    <div class="container">
        <div class="section-head">
            <span class="section-tag">Nos marques</span>
            <h2 class="section-title">Des constructeurs d'exception</h2>
        </div>

        <div class="brand-grid">
            <?php foreach ($marques as $marque) { ?>
                <a href="voitures.php?marque=<?php echo $marque['id_marque']; ?>" class="brand-card fade-up delay-<?php echo $marque['id_marque']; ?>">
                    <?php if (!empty($marque['image'])) { ?>
                        <img src="images/<?php echo e($marque['image']); ?>" alt="<?php echo e($marque['nom_marque']); ?>" loading="lazy">
                    <?php } ?>
                    <div class="overlay">
                        <h3><?php echo e($marque['nom_marque']); ?></h3>
                        <p><?php echo (int) $marque['nb_voitures']; ?> mod&egrave;le(s)</p>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>
</section>

<!-- ============ SERVICES ============ -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-tag">Nos services</span>
            <h2 class="section-title">Un accompagnement &agrave; chaque &eacute;tape</h2>
        </div>

        <div class="service-grid">
            <?php foreach ($services as $index => $service) { ?>
                <article class="service-card">
                    <div class="service-num"><?php echo $index + 1; ?></div>
                    <h3><?php echo e($service['nom_services']); ?></h3>
                    <p><?php echo e($service['description_services']); ?></p>
                    <span class="service-price"><?php echo $service['prix_services'] > 0 ? 'Rs ' . number_format($service['prix_services'], 0, ',', ' ') : 'Service gratuit'; ?></span>
                </article>
            <?php } ?>
        </div>

        <div class="text-center mt-4">
            <a href="services.php" class="btn btn-outline">Tous nos services</a>
        </div>
    </div>
</section>

<!-- ============ BANDEAU CTA ============ -->
<section class="cta-banner">
    <div class="container">
        <h2><?php echo e($contenuAccueil['cta_titre']); ?></h2>
        <p><?php echo e($contenuAccueil['cta_description']); ?></p>
        <div class="cta-actions">
            <a href="presentation.php" class="btn btn-main">D&eacute;couvrir l'essai</a>
            <a href="contact.php" class="btn btn-outline">Nous contacter</a>
        </div>
    </div>
</section>

<script>
// Carrousel du hero : affiche une image de fond a la fois.
(function () {
    var slides = document.querySelectorAll('.hero-slide');
    var current = 0;

    if (slides.length > 1) {
        setInterval(function () {
            slides[current].classList.remove('is-active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('is-active');
        }, 6000);
    }
})();
</script>

<?php include 'includes/footer.php'; ?>
