<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Administration'; ?> | SuperCar</title>

    <!-- Polices (mêmes que le site public) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS Admin -->
    <link href="assets/admin.css" rel="stylesheet">
</head>

<body>

<div class="admin-layout">
    <aside class="sidebar" role="navigation" aria-label="Menu principal administration">
        <a href="dashboard.php" class="sidebar-brand" aria-label="Accueil de l'administration SuperCar">
            <img src="../images/logo.png" alt="SuperCar" class="sidebar-brand__logo">
            <span class="sidebar-brand__text">Administration</span>
        </a>

        <nav class="sidebar-nav">
            <ul>
                <li class="sidebar-nav__item">
                    <a href="dashboard.php" class="sidebar-nav__link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'sidebar-nav__link--active' : ''; ?>">
                        <svg class="sidebar-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        <span class="sidebar-nav__label">Tableau de bord</span>
                    </a>
                </li>
                <li class="sidebar-nav__item">
                    <a href="voitures.php" class="sidebar-nav__link <?php echo basename($_SERVER['PHP_SELF']) === 'voitures.php' ? 'sidebar-nav__link--active' : ''; ?>">
                        <svg class="sidebar-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 13 10a5 5 0 0 0-5 5c0 1.1.4 2.1 1 2.8"/><path d="M9 17A3 3 0 1 1 9 11"/><path d="M19 17A3 3 0 1 1 19 11"/></svg>
                        <span class="sidebar-nav__label">Voitures</span>
                    </a>
                </li>
                <li class="sidebar-nav__item">
                    <a href="accueil.php" class="sidebar-nav__link <?php echo basename($_SERVER['PHP_SELF']) === 'accueil.php' ? 'sidebar-nav__link--active' : ''; ?>">
                        <svg class="sidebar-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/></svg>
                        <span class="sidebar-nav__label">Page d'accueil</span>
                    </a>
                </li>
                <li class="sidebar-nav__item">
                    <a href="essais.php" class="sidebar-nav__link <?php echo basename($_SERVER['PHP_SELF']) === 'essais.php' ? 'sidebar-nav__link--active' : ''; ?>">
                        <svg class="sidebar-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span class="sidebar-nav__label">Demande d'essai</span>
                    </a>
                </li>
                <li class="sidebar-nav__item">
                    <a href="messages.php" class="sidebar-nav__link <?php echo basename($_SERVER['PHP_SELF']) === 'messages.php' ? 'sidebar-nav__link--active' : ''; ?>">
                        <svg class="sidebar-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span class="sidebar-nav__label">Messages</span>
                    </a>
                </li>
                <li class="sidebar-nav__item">
                    <a href="services.php" class="sidebar-nav__link <?php echo basename($_SERVER['PHP_SELF']) === 'services.php' ? 'sidebar-nav__link--active' : ''; ?>">
                        <svg class="sidebar-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M12 1v6"/><path d="M12 17v6"/><path d="M4.22 4.22l4.24 4.24"/><path d="M15.54 15.54l4.24 4.24"/><path d="M1 12h6"/><path d="M17 12h6"/></svg>
                        <span class="sidebar-nav__label">Services</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <a href="../index.php" class="sidebar-footer__link" target="_blank" rel="noopener">
                <svg class="sidebar-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                <span>Voir le site</span>
            </a>
            <a href="logout.php" class="sidebar-footer__link">
                <svg class="sidebar-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <span>Déconnexion</span>
            </a>
        </div>
    </aside>

    <main class="content" role="main">
        <div class="topbar">
            <h1 class="topbar__title"><?php echo $pageTitle ?? 'Administration'; ?></h1>
            <div class="topbar__actions">
                <a href="../index.php" class="btn btn--secondary btn--sm" target="_blank" rel="noopener">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    Voir le site
                </a>
            </div>
        </div>
