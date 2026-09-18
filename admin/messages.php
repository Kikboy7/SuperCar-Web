<?php
include 'includes/auth.php';

$pageTitle = "Messages";
$messageInfo = "";

// Suppression d'un message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supprimer_message'])) {
    if (!csrf_verify()) {
        $messageInfo = "<div class=\"alert alert--danger\"><div class=\"alert__content\"><p class=\"alert__text\">Session de sécurité invalide.</p></div></div>";
    } else {
        $id_message = (int) $_POST['supprimer_message'];
        $stmt = $pdo->prepare("DELETE FROM message WHERE id_message = ?");
        $stmt->execute([$id_message]);
        $messageInfo = "<div class=\"alert alert--success\"><div class=\"alert__content\"><p class=\"alert__text\">Message supprimé.</p></div></div>";
    }
}

// Réponse ou changement de statut
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_message']) && !isset($_POST['supprimer_message'])) {
    if (!csrf_verify()) {
        $messageInfo = "<div class=\"alert alert--danger\"><div class=\"alert__content\"><p class=\"alert__text\">Session de sécurité invalide.</p></div></div>";
    } else {
        $id_message = $_POST['id_message'];
        $statut = $_POST['statut_message'];
        $reponse = trim($_POST['reponse_admin']);

        $stmt = $pdo->prepare("
            UPDATE message
            SET statut_message = ?, reponse_admin = ?, date_reponse = NOW()
            WHERE id_message = ?
        ");
        $stmt->execute([$statut, $reponse, $id_message]);
        $messageInfo = "<div class=\"alert alert--success\"><div class=\"alert__content\"><p class=\"alert__text\">Message mis à jour.</p></div></div>";
    }
}

// Récupérer les messages
$stmt = $pdo->query("
    SELECT message.*, client.id_client, client.telephone, client.adresse
    FROM message
    LEFT JOIN client ON message.id_client = client.id_client
    ORDER BY message.date_message DESC
");
$messages = $stmt->fetchAll();

// Stats
$stats = ['nouveau' => 0, 'en cours' => 0, 'traite' => 0];
foreach ($messages as $m) {
    if (isset($stats[$m['statut_message']])) $stats[$m['statut_message']]++;
}

include 'includes/header.php';
?>

<?php if ($messageInfo) echo $messageInfo; ?>

<!-- Résumé statuts -->
<div class="stats-grid" style="margin-bottom: var(--space-5);">
    <div class="card card--stats card--warning">
        <div class="card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div>
            <div class="card--stats__value"><?php echo $stats['nouveau']; ?></div>
            <div class="card--stats__label">Nouveaux</div>
        </div>
    </div>
    <div class="card card--stats card--info">
        <div class="card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <div>
            <div class="card--stats__value"><?php echo $stats['en cours']; ?></div>
            <div class="card--stats__label">En cours</div>
        </div>
    </div>
    <div class="card card--stats card--success">
        <div class="card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div>
            <div class="card--stats__value"><?php echo $stats['traite']; ?></div>
            <div class="card--stats__label">Traité</div>
        </div>
    </div>
    <div class="card card--stats card--neutral">
        <div class="card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div>
            <div class="card--stats__value"><?php echo count($messages); ?></div>
            <div class="card--stats__label">Total</div>
        </div>
    </div>
</div>

<!-- Tableau des messages -->
<div class="card">
    <div class="card__header">
        <h2 class="card__title">Messages clients</h2>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Expéditeur</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Statut / Réponse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg) { ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($msg['nom']); ?></strong><br>
                            <?php echo htmlspecialchars($msg['email']); ?>
                            <?php if ($msg['id_client']) { ?>
                                <br><span class="badge badge--info">Client connecté</span>
                                <br><span class="text-muted">Tel : <?php echo htmlspecialchars($msg['telephone'] ?? 'Non renseigné'); ?></span>
                                <br><span class="text-muted">Adresse : <?php echo htmlspecialchars($msg['adresse'] ?? 'Non renseignée'); ?></span>
                            <?php } else { ?>
                                <br><span class="badge badge--neutral">Visiteur</span>
                            <?php } ?>
                        </td>

                        <td class="table__cell--truncate" style="max-width: 320px;"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></td>

                        <td><?php echo htmlspecialchars($msg['date_message']); ?></td>

                        <td>
                            <form method="POST" style="display: flex; flex-direction: column; gap: var(--space-2);">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id_message" value="<?php echo $msg['id_message']; ?>">
                                <div class="select-wrapper" style="width: 100%;">
                                    <select name="statut_message" class="form-select">
                                        <option value="nouveau" <?php if ($msg['statut_message'] == 'nouveau') echo 'selected'; ?>>Nouveau</option>
                                        <option value="en cours" <?php if ($msg['statut_message'] == 'en cours') echo 'selected'; ?>>En cours</option>
                                        <option value="traite" <?php if ($msg['statut_message'] == 'traite') echo 'selected'; ?>>Traité</option>
                                    </select>
                                </div>
                                <textarea name="reponse_admin" class="form-textarea" placeholder="Réponse ou note interne" style="min-height: 70px;"><?php echo htmlspecialchars($msg['reponse_admin'] ?? ''); ?></textarea>
                                <?php if (!empty($msg['date_reponse'])) { ?>
                                    <small class="text-muted">Dernière réponse : <?php echo htmlspecialchars($msg['date_reponse']); ?></small>
                                <?php } ?>
                                <button type="submit" class="btn btn--primary btn--sm" style="align-self: flex-start;">Enregistrer</button>
                            </form>
                        </td>

                        <td>
                            <div class="table-actions">
                                <a class="btn btn--secondary btn--sm" href="mailto:<?php echo htmlspecialchars($msg['email']); ?>">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                    Email
                                </a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce message ?');">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="supprimer_message" value="<?php echo $msg['id_message']; ?>">
                                    <button type="submit" class="btn btn--danger btn--sm">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                <?php if (!$messages) { ?>
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