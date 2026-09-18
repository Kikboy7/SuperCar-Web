<?php
include 'includes/auth.php';

$pageTitle = "Tableau de bord";

// Compter les données principales
$totalVoitures = $pdo->query("SELECT COUNT(*) FROM voiture")->fetchColumn();
$totalEssais = $pdo->query("SELECT COUNT(*) FROM essai")->fetchColumn();
$totalMessages = $pdo->query("SELECT COUNT(*) FROM message")->fetchColumn();
$messagesNouveaux = $pdo->query("SELECT COUNT(*) FROM message WHERE statut_message = 'nouveau'")->fetchColumn();
$totalServices = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();

// Essais par statut
$essaisEnAttente = $pdo->query("SELECT COUNT(*) FROM essai WHERE statut = 'en attente'")->fetchColumn();
$essaisValides = $pdo->query("SELECT COUNT(*) FROM essai WHERE statut = 'valide'")->fetchColumn();
$essaisRefuses = $pdo->query("SELECT COUNT(*) FROM essai WHERE statut = 'refuse'")->fetchColumn();

include 'includes/header.php';
?>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="card card--stats card--primary">
        <div class="card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 13 10a5 5 0 0 0-5 5c0 1.1.4 2.1 1 2.8"/><path d="M9 17A3 3 0 1 1 9 11"/><path d="M19 17A3 3 0 1 1 19 11"/></svg>
        </div>
        <div>
            <div class="card--stats__value"><?php echo $totalVoitures; ?></div>
            <div class="card--stats__label">Voitures au catalogue</div>
            <a href="voitures.php" class="card--stats__meta">Gérer →</a>
        </div>
    </div>

    <div class="card card--stats card--warning">
        <div class="card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <div>
            <div class="card--stats__value"><?php echo $totalEssais; ?></div>
            <div class="card--stats__label">Demandes d'essai</div>
            <div class="card--stats__meta"><?php echo $essaisEnAttente; ?> en attente · <?php echo $essaisValides; ?> validées · <?php echo $essaisRefuses; ?> refusées</div>
        </div>
    </div>

    <div class="card card--stats card--info">
        <div class="card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div>
            <div class="card--stats__value"><?php echo $totalMessages; ?></div>
            <div class="card--stats__label">Messages clients</div>
            <div class="card--stats__meta"><?php echo $messagesNouveaux; ?> nouveau<?php echo $messagesNouveaux > 1 ? 'x' : ''; ?></div>
        </div>
    </div>

    <div class="card card--stats card--success">
        <div class="card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v6"/><path d="M12 17v6"/><path d="M4.22 4.22l4.24 4.24"/><path d="M15.54 15.54l4.24 4.24"/><path d="M1 12h6"/><path d="M17 12h6"/></svg>
        </div>
        <div>
            <div class="card--stats__value"><?php echo $totalServices; ?></div>
            <div class="card--stats__label">Services proposés</div>
            <a href="services.php" class="card--stats__meta">Gérer →</a>
        </div>
    </div>
</div>

<!-- Derniers essais -->
<div class="card">
    <div class="card__header">
        <h2 class="card__title">Dernières demandes d'essai</h2>
        <a href="essais.php" class="btn btn--secondary btn--sm">Voir tout</a>
    </div>

    <?php
    $stmt = $pdo->query("
        SELECT e.*, c.nom, c.email, v.modele, m.nom_marque
        FROM essai e
        JOIN client c ON e.id_client = c.id_client
        JOIN voiture v ON e.id_voiture = v.id_voiture
        JOIN marque m ON v.id_marque = m.id_marque
        ORDER BY e.date_demande DESC
        LIMIT 5
    ");
    $derniersEssais = $stmt->fetchAll();
    ?>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Voiture</th>
                    <th>Date essai</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($derniersEssais) { ?>
                    <?php foreach ($derniersEssais as $essai) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($essai['nom']); ?></td>
                            <td><?php echo htmlspecialchars($essai['nom_marque'] . ' ' . $essai['modele']); ?></td>
                            <td><?php echo htmlspecialchars($essai['date_essai']); ?></td>
                            <td>
                                <?php
                                $badgeClass = match($essai['statut']) {
                                    'valide' => 'badge--success',
                                    'refuse' => 'badge--danger',
                                    default => 'badge--warning'
                                };
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($essai['statut']); ?></span>
                            </td>
                            <td>
                                <a href="essais.php" class="btn btn--ghost btn--sm">Traiter</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5">
                            <div class="table-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <p class="table-empty__title">Aucune demande d'essai</p>
                                <p class="table-empty__text">Les demandes apparaîtront ici</p>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Derniers messages -->
<div class="card">
    <div class="card__header">
        <h2 class="card__title">Derniers messages</h2>
        <a href="messages.php" class="btn btn--secondary btn--sm">Voir tout</a>
    </div>

    <?php
    $stmt = $pdo->query("
        SELECT m.*, c.nom
        FROM message m
        LEFT JOIN client c ON m.id_client = c.id_client
        ORDER BY m.date_envoi DESC
        LIMIT 5
    ");
    $derniersMessages = $stmt->fetchAll();
    ?>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Expéditeur</th>
                    <th>Sujet</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($derniersMessages) { ?>
                    <?php foreach ($derniersMessages as $msg) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($msg['nom'] ?? 'Visiteur'); ?></td>
                            <td class="table__cell--truncate"><?php echo htmlspecialchars($msg['sujet']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($msg['date_envoi']))); ?></td>
                            <td>
                                <?php
                                $badgeClass = match($msg['statut_message']) {
                                    'nouveau' => 'badge--warning',
                                    'lu' => 'badge--info',
                                    'repondu' => 'badge--success',
                                    default => 'badge--neutral'
                                };
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($msg['statut_message']); ?></span>
                            </td>
                            <td>
                                <a href="messages.php" class="btn btn--ghost btn--sm">Lire</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5">
                            <div class="table-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                <p class="table-empty__title">Aucun message</p>
                                <p class="table-empty__text">Les messages clients apparaîtront ici</p>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

    </main>
</div>

</body>
</html>