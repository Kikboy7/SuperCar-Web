<?php
/**
 * presentation.php - Page de presentation de la reservation d'essai.
 * Explique pourquoi faire un essai chez SuperCar et comment ca marche.
 */
$pageTitle = "R&eacute;server un essai";
include 'includes/header.php';

$isLogged = isset($_SESSION['client']);
?>

<section class="hero">
    <div class="container">
        <div class="hero-content fade-up">
            <span class="hero-tag">R&eacute;server un essai</span>
            <h1>Vivez une <span>exp&eacute;rience</span> de conduite unique</h1>
            <p>D&eacute;couvrez nos v&eacute;hicules dans des conditions r&eacute;elles de conduite&nbsp;: performance, confort, pr&eacute;cision.</p>
            <div class="hero-actions">
                <?php if ($isLogged) { ?>
                    <a href="demande_essai.php" class="btn btn-main">Demander un essai</a>
                <?php } else { ?>
                    <a href="login.php" class="btn btn-main">Se connecter</a>
                    <a href="register.php" class="btn btn-outline">Cr&eacute;er un compte</a>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-tag">Pourquoi un essai&nbsp;?</span>
            <h2 class="section-title">Bien plus qu'un simple test</h2>
            <p class="section-sub">
                R&eacute;server un essai chez SuperCar, c'est une v&eacute;ritable immersion dans
                l'univers de la performance, du luxe et de l'innovation automobile.
            </p>
        </div>

        <div class="car-grid">
            <div class="service-card">
                <div class="service-num">&#128293;</div>
                <h3>Performance r&eacute;elle</h3>
                <p>Acc&eacute;l&eacute;ration, tenue de route, pr&eacute;cision : ressentez chaque d&eacute;tail de conduite.</p>
            </div>
            <div class="service-card">
                <div class="service-num">&#128100;</div>
                <h3>Accompagnement expert</h3>
                <p>Nos conseillers r&eacute;pondent &agrave; vos questions et vous guident vers le bon mod&egrave;le.</p>
            </div>
            <div class="service-card">
                <div class="service-num">&#9989;</div>
                <h3>Un choix &eacute;clair&eacute;</h3>
                <p>L'essai est une &eacute;tape essentielle pour choisir votre future voiture en toute confiance.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background: var(--noir-2); border-top: 1px solid var(--bordure); border-bottom: 1px solid var(--bordure);">
    <div class="container">
        <div class="section-head">
            <span class="section-tag">Comment &ccedil;a marche&nbsp;?</span>
            <h2 class="section-title">Trois &eacute;tapes simples</h2>
        </div>

        <div class="steps">
            <div class="step-card">
                <div class="num">1</div>
                <h3><?php echo $isLogged ? 'Acc&eacute;der au catalogue' : 'Cr&eacute;er un compte'; ?></h3>
                <p><?php echo $isLogged ? 'Connectez-vous pour r&eacute;server rapidement.' : 'Inscrivez-vous en quelques secondes.'; ?></p>
            </div>
            <div class="step-card">
                <div class="num">2</div>
                <h3>Choisir une voiture</h3>
                <p>Parcourez notre catalogue et s&eacute;lectionnez le mod&egrave;le qui vous pla&icirc;t.</p>
            </div>
            <div class="step-card">
                <div class="num">3</div>
                <h3>R&eacute;server un essai</h3>
                <p>Choisissez une date, un horaire, et c'est parti&nbsp;!</p>
            </div>
        </div>

        <div class="text-center mt-4">
            <?php if ($isLogged) { ?>
                <a href="demande_essai.php" class="btn btn-main">Demander un essai</a>
            <?php } else { ?>
                <a href="register.php" class="btn btn-main">Cr&eacute;er un compte</a>
                <a href="login.php" class="btn btn-outline">Se connecter</a>
            <?php } ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>