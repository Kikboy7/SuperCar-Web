<?php
include 'includes/auth.php';

$pageTitle = "Demandes d'essai";
$message = "";

// Changement de statut d'une demande
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!csrf_verify()) {
        $message = "<div class=\"alert alert--danger\"><div class=\"alert__content\"><p class=\"alert__text\">Session de sécurité invalide.</p></div></div>";
    } else {
        $id_essai = $_POST['id_essai'];
        $statut = $_POST['statut'];

        $stmt = $pdo->prepare("UPDATE essai SET statut = ? WHERE id_essai = ?");
        $stmt->execute([$statut, $id_essai]);

        $message = "<div class=\"alert alert--success\"><div class=\"alert__content\"><p class=\"alert__text\">Statut mis à jour.</p></div></div>";
    }
}

// On récupère les demandes avec les informations du client et de la voiture
$stmt = $pdo->query("
    SELECT e.*, c.nom, c.email, c.telephone, v.modele, m.nom_marque
    FROM essai e
    JOIN client c ON e.id_client = c.id_client
    JOIN voiture v ON e.id_voiture = v.id_voiture
    JOIN marque m ON v.id_marque = m.id_marque
    ORDER BY e.date_demande DESC
");
$essais = $stmt->fetchAll();

// Compter par statut pour le résumé
$stats = [
    'en attente' => 0,
    'valide' => 0,
    'refuse' => 0,
];
foreach ($essais as $e) {
    if (isset($stats[$e['statut']])) $stats[$e['statut']]++;
}

include 'includes/header.php';
?>

<?php if ($message) echo $message; ?>

<!-- Résumé statuts -->
<div class="stats-grid" style="margin-bottom: var(--space-5);">
    <div class="card card--stats card--warning">
        <div class="card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div>
            <div class="card--stats__value"><?php echo $stats['en attente']; ?></div>
            <div class="card--stats__label">En attente</div>
        </div>
    </div>
    <div class="card card--stats card--success">
        <div class="card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div>
            <div class="card--stats__value"><?php echo $stats['valide']; ?></div>
            <div class="card--stats__label">Validées</div>
        </div>
    </div>
    <div class="card card--stats card--danger">
        <div class="card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        </div>
        <div>
            <div class="card--stats__value"><?php echo $stats['refuse']; ?></div>
            <div class="card--stats__label">Refusées</div>
        </div>
    </div>
    <div class="card card--stats card--info">
        <div class="card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <div>
            <div class="card--stats__value"><?php echo count($essais); ?></div>
            <div class="card--stats__label">Total</div>
        </div>
    </div>
</div>

<!-- Tableau des essais -->
<div class="card">
    <div class="card__header">
        <h2 class="card__title">Toutes les demandes</h2>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Contact</th>
                    <th>Voiture</th>
                    <th>Date essai</th>
                    <th>Heure</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($essais) { ?>
                    <?php foreach ($essais as $essai) { ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($essai['nom']); ?></strong></td>
                            <td>
                                <?php echo htmlspecialchars($essai['email']); ?><br>
                                <span class="text-muted"><?php echo htmlspecialchars($essai['telephone']); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($essai['nom_marque'] . " " . $essai['modele']); ?></td>
                            <td><?php echo htmlspecialchars($essai['date_essai']); ?></td>
                            <td><?php echo $essai['heure_essai'] ? htmlspecialchars(substr($essai['heure_essai'], 0, 5)) : '<span class="text-muted">Non renseignée</span>'; ?></td>
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
                                <form method="POST" class="table-actions">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id_essai" value="<?php echo $essai['id_essai']; ?>">
                                    <div class="select-wrapper" style="width: 140px;">
                                        <select name="statut" class="form-select form-select--sm">
                                            <option value="en attente" <?php if ($essai['statut'] == 'en attente') echo 'selected'; ?>>En attente</option>
                                            <option value="valide" <?php if ($essai['statut'] == 'valide') echo 'selected'; ?>>Validée</option>
                                            <option value="refuse" <?php if ($essai['statut'] == 'refuse') echo 'selected'; ?>>Refusée</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn--primary btn--sm">Modifier</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7">
                            <div class="table-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <p class="table-empty__title">Aucune demande d'essai</p>
                                <p class="table-empty__text">Les demandes des clients apparaîtront ici</p>
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