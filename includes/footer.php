</main><!-- fin du contenu principal -->

<footer class="super-footer">
    <div class="footer-wrap">
        <div class="footer-main">
            <div class="footer-brand">
                <a href="index.php" class="footer-logo" aria-label="SuperCar — Accueil">
                    <?php echo supercar_logo(); ?>
                </a>
                <div class="footer-brand-line">L'automobile, avec exigence.</div>
                <p>Des véhicules d'exception, des conseils personnalisés et un accompagnement du premier essai à la livraison.</p>
                <a href="contact.php" class="footer-contact-link">Parlons de votre projet <span aria-hidden="true">↗</span></a>
            </div>

            <nav class="footer-column" aria-label="Découvrir SuperCar">
                <h2>Découvrir</h2>
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="voitures.php">Notre catalogue</a></li>
                    <li><a href="services.php">Nos services</a></li>
                    <li><a href="presentation.php">L'expérience d'un essai</a></li>
                </ul>
            </nav>

            <nav class="footer-column" aria-label="Espace client">
                <h2>Votre espace</h2>
                <ul>
                    <li><a href="compte.php">Mon compte</a></li>
                    <li><a href="demande_essai.php">Demande d’essais</a></li>
                    <li><a href="compte.php#tab-reservations">Mes réservations</a></li>
                    <li><a href="contact.php">Nous contacter</a></li>
                </ul>
            </nav>

            <div class="footer-column footer-location">
                <h2>Nous rencontrer</h2>
                <address><?php echo e($coordonneesSite['adresse']); ?><br><?php echo e($coordonneesSite['ville'] . ', ' . $coordonneesSite['pays']); ?></address>
                <a href="mailto:<?php echo e($coordonneesSite['email']); ?>"><?php echo e($coordonneesSite['email']); ?></a>
                <p class="footer-hours"><?php echo e($coordonneesSite['horaires_semaine']); ?><br><?php echo e($coordonneesSite['horaires_samedi']); ?><?php if ($coordonneesSite['horaires_complement'] !== '') { ?><br><?php echo e($coordonneesSite['horaires_complement']); ?><?php } ?></p>
                <a href="contact.php" class="footer-location-link">Coordonnées et accès <span aria-hidden="true">→</span></a>
            </div>
        </div>

        <div class="footer-assurances" aria-label="Nos engagements">
            <span><span class="footer-assurance-dot" aria-hidden="true"></span>Sélection premium</span>
            <span><span class="footer-assurance-dot" aria-hidden="true"></span>Essais accompagnés</span>
            <span><span class="footer-assurance-dot" aria-hidden="true"></span>Conseils personnalisés</span>
        </div>

        <div class="footer-bottom">
            <span>&copy; <?php echo date('Y'); ?> SuperCar. Tous droits réservés.</span>
            <span class="footer-project">Projet pédagogique · BTS SIO SLAM</span>
            <div class="footer-bottom-links">
                <a href="admin/login.php">Administration</a>
                <a href="#haut">Retour en haut <span aria-hidden="true">↑</span></a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Menu mobile : ouvre / ferme la liste des liens au clic sur le hamburger.
const navToggle = document.getElementById('navToggle');
const navCollapse = document.getElementById('navCollapse');
function fermerMenu() {
    navCollapse.classList.remove('open');
    navToggle.setAttribute('aria-expanded', 'false');
    navToggle.setAttribute('aria-label', 'Ouvrir le menu');
}
navToggle.addEventListener('click', function () {
    const ouvert = navCollapse.classList.toggle('open');
    navToggle.setAttribute('aria-expanded', String(ouvert));
    navToggle.setAttribute('aria-label', ouvert ? 'Fermer le menu' : 'Ouvrir le menu');
});
document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && navCollapse.classList.contains('open')) {
        fermerMenu();
        navToggle.focus();
    }
});
navCollapse.querySelectorAll('a').forEach(function (lien) { lien.addEventListener('click', fermerMenu); });
document.querySelectorAll('.nav-links a.active').forEach(function (lien) { lien.setAttribute('aria-current', 'page'); });

// Theme clair / sombre : le choix est conserve dans le navigateur.
const themeToggle = document.getElementById('themeToggle');

function mettreAJourBoutonTheme() {
    const themeClair = document.documentElement.getAttribute('data-theme') === 'light';
    const texte = themeClair ? 'Activer le thème sombre' : 'Activer le thème clair';

    themeToggle.setAttribute('aria-label', texte);
    themeToggle.setAttribute('title', texte);
}

mettreAJourBoutonTheme();

themeToggle.addEventListener('click', function () {
    const themeClair = document.documentElement.getAttribute('data-theme') === 'light';

    if (themeClair) {
        document.documentElement.removeAttribute('data-theme');
        localStorage.setItem('supercar-theme', 'dark');
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem('supercar-theme', 'light');
    }

    mettreAJourBoutonTheme();
});
</script>

</body>
</html>
