</main><!-- fin du contenu principal -->

<!-- Styles du footer : voir style.css (section FOOTER). Le footer utilise
     les memes variables que le reste du site, donc il suit le theme. -->
<footer class="super-footer">
    <div class="footer-wrap">

        <div class="footer-main">

            <div class="footer-brand">
                <div class="footer-brand-name">SuperCar</div>
                <div class="footer-brand-line">Premium automotive experience</div>
                <p>
                    SuperCar s&eacute;lectionne des v&eacute;hicules premium pour offrir une exp&eacute;rience
                    automobile moderne, &eacute;l&eacute;gante et orient&eacute;e performance.
                </p>
            </div>

            <div class="footer-badges">
                <span class="footer-badge">V&eacute;hicules premium</span>
                <span class="footer-badge">Essais sur r&eacute;servation</span>
                <span class="footer-badge">Accompagnement client</span>
            </div>

            <div class="footer-badges">
                <a href="#" class="footer-link">Mentions l&eacute;gales</a>
                <a href="#" class="footer-link">Confidentialit&eacute;</a>
                <a href="admin/login.php" class="footer-link">Administration</a>
            </div>

        </div>

        <div class="footer-bottom">
            <span>&copy; 2026 SuperCar. Tous droits r&eacute;serv&eacute;s.</span>
            <span>Projet BTS SIO - SLAM</span>
        </div>

    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Menu mobile : ouvre / ferme la liste des liens au clic sur le hamburger.
document.getElementById('navToggle').addEventListener('click', function () {
    document.getElementById('navCollapse').classList.toggle('open');
});

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
