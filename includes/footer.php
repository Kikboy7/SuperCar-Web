
<style>
/* ================= FOOTER PREMIUM ================= */

.super-footer {
    position: relative;
    background: radial-gradient(circle at top, #151515 0%, #050505 55%, #000 100%);
    color: #aaa;
    margin-top: 80px;
    font-family: 'Segoe UI', sans-serif;
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

@keyframes footerLight {
    from {
        left: -40%;
    }
    to {
        left: 100%;
    }
}

.footer-wrap {
    max-width: 1180px;
    margin: auto;
    padding: 42px 24px 24px;
}

.footer-main {
    display: grid;
    grid-template-columns: 1.2fr 1fr auto;
    gap: 35px;
    align-items: center;
}

.footer-signature {
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-bottom: 14px;
}

.footer-brand-name {
    color: #fff;
    font-size: 30px;
    font-weight: 800;
    letter-spacing: 1px;
    line-height: 1;
    transition: 0.3s ease;
}

.footer-brand-name:hover {
    color: #f39c12;
    text-shadow: 0 0 14px rgba(243,156,18,0.35);
}

.footer-brand-line {
    color: #f39c12;
    font-size: 12px;
    letter-spacing: 3px;
    text-transform: uppercase;
}

.footer-brand p {
    margin: 0;
    max-width: 440px;
    font-size: 14px;
    line-height: 1.7;
    color: #8d8d8d;
}

.footer-services {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: center;
}

.footer-badge {
    position: relative;
    padding: 9px 15px;
    border-radius: 999px;
    font-size: 13px;
    color: #cfcfcf;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    overflow: hidden;
    transition: 0.3s ease;
}

.footer-badge::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, transparent, rgba(243,156,18,0.18), transparent);
    transform: translateX(-100%);
    transition: 0.5s ease;
}

.footer-badge:hover::before {
    transform: translateX(100%);
}

.footer-badge:hover {
    color: #f39c12;
    border-color: rgba(243,156,18,0.45);
    transform: translateY(-3px);
}

.footer-social {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
}

.social-link {
    color: #aaa;
    text-decoration: none;
    transition: 0.3s ease;
    display: inline-flex;
}

.social-link svg {
    width: 24px;
    height: 24px;
    fill: currentColor;
    transition: 0.3s ease;
}

.social-link:hover {
    color: #f39c12;
    transform: translateY(-5px) scale(1.08);
    filter: drop-shadow(0 0 8px rgba(243,156,18,0.5));
}

.footer-bottom {
    margin-top: 30px;
    padding-top: 18px;
    border-top: 1px solid rgba(255,255,255,0.07);
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    color: #666;
}

.footer-bottom-links {
    display: flex;
    gap: 22px;
}

.footer-bottom-links a {
    color: #777;
    text-decoration: none;
    position: relative;
    transition: 0.3s ease;
}

.footer-bottom-links a::after {
    content: "";
    position: absolute;
    width: 0;
    height: 1px;
    left: 0;
    bottom: -4px;
    background: #f39c12;
    transition: 0.3s ease;
}

.footer-bottom-links a:hover {
    color: #f39c12;
}

.footer-bottom-links a:hover::after {
    width: 100%;
}

@media (max-width: 900px) {
    .footer-main {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .footer-brand p {
        margin: auto;
    }

    .footer-services {
        justify-content: center;
    }

    .footer-social {
        justify-content: center;
    }

    .footer-bottom {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }
}
</style>


<footer class="super-footer">
    <div class="footer-wrap">

        <div class="footer-main">

            <!-- SIGNATURE + TEXTE -->
            <div class="footer-brand">
                <div class="footer-signature">
                    <span class="footer-brand-name">SuperCar</span>
                    <span class="footer-brand-line">Premium automotive experience</span>
                </div>

                <p>
                    SuperCar sélectionne des véhicules premium pour offrir une expérience
                    automobile moderne, élégante et orientée performance.
                </p>
            </div>

            <!-- SERVICES -->
            <div class="footer-services">
                <span class="footer-badge">Véhicules premium</span>
                <span class="footer-badge">Essais sur réservation</span>
                <span class="footer-badge">Accompagnement client</span>
            </div>

            <!-- RÉSEAUX SOCIAUX -->
            <div class="footer-social">

                <!-- Facebook -->
                <a href="#" class="social-link" aria-label="Facebook">
                    <svg viewBox="0 0 24 24">
                        <path d="M22 12a10 10 0 1 0-11.6 9.9v-7h-2.5V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.5V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12z"/>
                    </svg>
                </a>

                <!-- Instagram -->
                <a href="#" class="social-link" aria-label="Instagram">
                    <svg viewBox="0 0 24 24">
                        <path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm10 2H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3zm-5 3.5A4.5 4.5 0 1 1 7.5 12 4.5 4.5 0 0 1 12 7.5zm0 2A2.5 2.5 0 1 0 14.5 12 2.5 2.5 0 0 0 12 9.5zM17.8 6.2a1 1 0 1 1-1 1 1 1 0 0 1 1-1z"/>
                    </svg>
                </a>

                <!-- LinkedIn -->
                <a href="#" class="social-link" aria-label="LinkedIn">
                    <svg viewBox="0 0 24 24">
                        <path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3V9zm7 0h3.8v1.7h.1c.5-1 1.8-2 3.7-2 4 0 4.7 2.6 4.7 6V21h-4v-5.6c0-1.3 0-3-1.9-3s-2.1 1.4-2.1 2.9V21h-4V9z"/>
                    </svg>
                </a>

            </div>

        </div>

        <div class="footer-bottom">
            <span>© 2026 SuperCar. Tous droits réservés.</span>

            <div class="footer-bottom-links">
                <a href="#">Mentions légales</a>
                <a href="#">Confidentialité</a>
            </div>
        </div>

    </div>
</footer>
