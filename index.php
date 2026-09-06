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

// Les 3 premiers services proposes.
$services = $pdo->query("SELECT * FROM services ORDER BY id_services LIMIT 3")->fetchAll();
?>

<!-- ============ HERO + CARROUSEL ============ -->
<section class="hero">

    <!-- Carrousel d'images de fond -->
    <div class="hero-slider">
        <img src="images/background1.webp" alt="" class="hero-slide is-active" data-slide="0">
        <img src="images/background2.png" alt="" class="hero-slide" data-slide="1">
        <img src="images/background3.png" alt="" class="hero-slide" data-slide="2">
        <img src="images/background4.png" alt="" class="hero-slide" data-slide="3">
    </div>

    <!-- Fleches de navigation -->
    <button class="hero-slider-btn hero-slider-prev" type="button" aria-label="Image suivante">&lsaquo;</button>
    <button class="hero-slider-btn hero-slider-next" type="button" aria-label="Image suivante">&rsaquo;</button>

    <!-- Points de navigation -->
    <div class="hero-slider-dots">
        <button type="button" class="is-active" data-dot="0" aria-label="Image 1"></button>
        <button type="button" data-dot="1" aria-label="Image 2"></button>
        <button type="button" data-dot="2" aria-label="Image 3"></button>
        <button type="button" data-dot="3" aria-label="Image 4"></button>
    </div>

    <div class="container">
        <div class="hero-content fade-up">
            <span class="hero-tag">Concessionnaire premium &mdash; depuis 2009</span>
            <h1>D&eacute;couvrez votre<br><span>prochaine voiture.</span></h1>
            <p>
                Performance, luxe et innovation. SuperCar s&eacute;lectionne des v&eacute;hicules
                d'exception et vous accompagne du premier essai &agrave; la livraison.
            </p>

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
                    <span>Marques internation</span>
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
            <?php foreach ($services as $service) { ?>
                <article class="service-card">
                    <div class="service-num"><?php echo $service['id_services']; ?></div>
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
        <h2>Envieux de tester une voiture ?</h2>
        <p>R&eacute;servez votre essai en quelques clics et vivez une exp&eacute;rience de conduite unique.</p>
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
    var dots = document.querySelectorAll('.hero-slider-dots button');
    var current = 0;
    var timer = null;

    function showSlide(index) {
        if (!slides.length) return;
        current = (index + slides.length) % slides.length;
        for (var i = 0; i < slides.length; i++) {
            slides[i].classList.toggle('is-active', i === current);
            dots[i].classList.toggle('is-active', i === current);
        }
    }

    function next() { showSlide(current + 1); }
    function prev() { showSlide(current - 1); }

    function restart() {
        clearInterval(timer);
        timer = setInterval(next, 6000);
    }

    // Fleches
    var btnNext = document.querySelector('.hero-slider-next');
    var btnPrev = document.querySelector('.hero-slider-prev');
    if (btnNext) btnNext.addEventListener('click', function () { next(); restart(); });
    if (btnPrev) btnPrev.addEventListener('click', function () { prev(); restart(); });

    // Points
    for (var i = 0; i < dots.length; i++) {
        (function (index) {
            dots[index].addEventListener('click', function () { showSlide(index); restart(); });
        })(i);
    }

    // Lancement automatique
    restart();
})();
</script>

<?php include 'includes/footer.php'; ?>