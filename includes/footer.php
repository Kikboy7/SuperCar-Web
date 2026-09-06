</main><!-- fin du contenu principal -->

<style>
/* ================= FOOTER PREMIUM ================= */
.super-footer {
    position: relative;
    margin-top: 80px;
    background: radial-gradient(circle at top, #141414 0%, #070707 60%, #000 100%);
    color: #9a9a9a;
    border-top: 1px solid rgba(255,255,255,0.06);
    font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
    overflow: hidden;
}

.super-footer::before {
    content: "";
    position: absolute;
    top: 0;
    left: -40%;
    width: 40%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #f39c12, transparent);
    animation: footerLight 4s linear infinite;
}
@keyframes footerLight { from { left: -40%; } to { left: 100%; } }

.footer-wrap { max-width: 1200px; margin: auto; padding: 46px 24px 24px; }

.footer-main {
    display: grid;
    grid-template-columns: 1.4fr 1fr auto;
    gap: 36px;
    align-items: center;
    margin-bottom: 34px;
}

.footer-brand-name {
    font-family: var(--font-titre);
    color: #fff;
    font-size: 30px;
    font-weight: 800;
    letter-spacing: 1px;
}
.footer-brand-line { color: #f39c12; font-size: 12px; letter-spacing: 3px; text-transform: uppercase; margin: 4px 0 14px; }
.footer-brand p { max-width: 440px; color: #888; font-size: 14px; line-height: 1.75; }

.footer-badges { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; }
.footer-badge {
    padding: 9px 15px;
    border-radius: 999px;
    font-size: 13px;
    color: #cfcfcf;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.09);
    transition: all .3s ease;
}
.footer-badge:hover { color: #f39c12; border-color: rgba(243,156,18,0.5); transform: translateY(-2px); }

.footer-link { color: #777; font-size: 14px; font-weight: 500; }
.footer-link:hover { color: #f39c12; }

@media (max-width: 900px) {
    .footer-main { grid-template-columns: 1fr; text-align: center; }
    .footer-brand p { margin: 0 auto; }
    .footer-bottom { flex-direction: column; gap: 12px; text-align: center; }
}

.footer-bottom {
    padding-top: 22px;
    border-top: 1px solid rgba(255,255,255,0.07);
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    color: #666;
}
</style>

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
</script>

</body>
</html>