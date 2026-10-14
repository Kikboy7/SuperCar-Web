<?php
include 'includes/auth.php';

$pageTitle = "Services";
$message = "";

// Ajouter ou modifier un service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom_services'])) {
    if (!csrf_verify()) {
        $message = "<div class=\"alert alert--danger\"><div class=\"alert__content\"><p class=\"alert__text\">Session de sécurité invalide.</p></div></div>";
    } else {
        $id_services = $_POST['id_services'] ?? "";
        $nom = trim($_POST['nom_services']);
        $description = trim($_POST['description_services']);
        $prix = $_POST['prix_services'];
        $image = trim($_POST['image_services'] ?? '');
        $details = trim($_POST['details_services'] ?? '');
        $points = trim($_POST['points_services'] ?? '');

        if ($id_services) {
            $stmt = $pdo->prepare("
                UPDATE services
                SET nom_services = ?, description_services = ?, details_services = ?, points_services = ?, prix_services = ?, image_services = ?
                WHERE id_services = ?
            ");
            $stmt->execute([$nom, $description, $details, $points, $prix, $image, $id_services]);
            $message = "<div class=\"alert alert--success\"><div class=\"alert__content\"><p class=\"alert__text\">Service modifié.</p></div></div>";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO services (nom_services, description_services, details_services, points_services, prix_services, image_services)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$nom, $description, $details, $points, $prix, $image]);
            $message = "<div class=\"alert alert--success\"><div class=\"alert__content\"><p class=\"alert__text\">Service ajouté.</p></div></div>";
        }
    }
}

// Supprimer un service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supprimer_service'])) {
    if (!csrf_verify()) {
        $message = "<div class=\"alert alert--danger\"><div class=\"alert__content\"><p class=\"alert__text\">Session de sécurité invalide.</p></div></div>";
    } else {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id_services = ?");
        $stmt->execute([(int) $_POST['supprimer_service']]);
        $message = "<div class=\"alert alert--success\"><div class=\"alert__content\"><p class=\"alert__text\">Service supprimé.</p></div></div>";
    }
}

// Récupérer un service pour modification
$serviceEdit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id_services = ?");
    $stmt->execute([$_GET['edit']]);
    $serviceEdit = $stmt->fetch();
}

$services = $pdo->query("
    SELECT * FROM services
    ORDER BY FIELD(
        nom_services,
        'Conseil d''achat',
        'Essai personnalisé',
        'Démarches administratives',
        'Livraison à domicile',
        'Reprise de votre ancien véhicule',
        'Financement sur mesure',
        'Garantie 12 mois',
        'Detailing & entretien'
    ), id_services
")->fetchAll();

include 'includes/header.php';
?>

<?php if ($message) echo $message; ?>

<!-- Formulaire ajout/modification -->
<div class="card">
    <div class="card__header">
        <h2 class="card__title"><?php echo $serviceEdit ? "Modifier un service" : "Ajouter un service"; ?></h2>
    </div>

    <form method="POST" class="form-grid">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id_services" value="<?php echo htmlspecialchars($serviceEdit['id_services'] ?? ''); ?>">

        <div class="form-group">
            <label class="form-label form-label--required" for="nom_services">Nom du service</label>
            <input type="text" id="nom_services" name="nom_services" class="form-input" placeholder="Nom du service" value="<?php echo htmlspecialchars($serviceEdit['nom_services'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label form-label--required" for="prix_services">Prix (Rs)</label>
            <input type="number" id="prix_services" name="prix_services" class="form-input" placeholder="0" value="<?php echo htmlspecialchars($serviceEdit['prix_services'] ?? '0'); ?>" required min="0">
        </div>

        <div class="form-group">
            <label class="form-label" for="image_services">Image (fichier dans images/)</label>
            <input type="text" id="image_services" name="image_services" class="form-input" placeholder="service_essai.jpg" value="<?php echo htmlspecialchars($serviceEdit['image_services'] ?? ''); ?>">
            <?php if (!empty($serviceEdit['image_services'])) { ?>
                <img src="../<?php echo e(image_versionnee($serviceEdit['image_services'])); ?>" alt="" class="table-thumb" style="margin-top:8px;">
            <?php } ?>
        </div>

        <div class="form-group form-group--full">
            <label class="form-label" for="description_services">Description courte (cartes)</label>
            <textarea id="description_services" name="description_services" class="form-textarea" placeholder="Description"><?php echo htmlspecialchars($serviceEdit['description_services'] ?? ''); ?></textarea>
        </div>

        <div class="form-group form-group--full">
            <label class="form-label" for="details_services">Présentation complète (fiche détail)</label>
            <textarea id="details_services" name="details_services" class="form-textarea" placeholder="Texte affiché sur la fiche du service" style="min-height:140px;"><?php echo htmlspecialchars($serviceEdit['details_services'] ?? ''); ?></textarea>
        </div>

        <div class="form-group form-group--full">
            <label class="form-label" for="points_services">Points forts (un par ligne, affichés en checklist)</label>
            <textarea id="points_services" name="points_services" class="form-textarea" placeholder="Exemple :&#10;Inspection gratuite&#10;Offre sous 24 heures" style="min-height:90px;"><?php echo htmlspecialchars($serviceEdit['points_services'] ?? ''); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn--primary"><?php echo $serviceEdit ? "Modifier" : "Ajouter"; ?></button>
            <?php if ($serviceEdit) { ?>
                <a href="services.php" class="btn btn--secondary">Annuler</a>
            <?php } ?>
        </div>
    </form>
</div>

<!-- Liste des services -->
<div class="card">
    <div class="card__header">
        <h2 class="card__title">Liste des services</h2>
        <span class="badge badge--neutral"><?php echo count($services); ?> service(s)</span>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service) { ?>
                    <tr>
                        <td>
                            <?php if (!empty($service['image_services'])) { ?>
                                <img src="../<?php echo e(image_versionnee($service['image_services'])); ?>" alt="" class="table-thumb">
                            <?php } else { ?>
                                <span class="text-muted">—</span>
                            <?php } ?>
                        </td>
                        <td><strong><?php echo htmlspecialchars($service['nom_services']); ?></strong></td>
                        <td class="table__cell--truncate" style="max-width: 300px;"><?php echo htmlspecialchars($service['description_services']); ?></td>
                        <td>Rs <?php echo number_format($service['prix_services'], 0, ',', ' '); ?></td>
                        <td>
                            <div class="table-actions">
                                <a class="btn btn--secondary btn--sm" href="services.php?edit=<?php echo $service['id_services']; ?>">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Modifier
                                </a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce service ?');">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="supprimer_service" value="<?php echo $service['id_services']; ?>">
                                    <button type="submit" class="btn btn--danger btn--sm">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                <?php if (!$services) { ?>
                    <tr>
                        <td colspan="5">
                            <div class="table-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M12 1v6"/><path d="M12 17v6"/><path d="M4.22 4.22l4.24 4.24"/><path d="M15.54 15.54l4.24 4.24"/><path d="M1 12h6"/><path d="M17 12h6"/></svg>
                                <p class="table-empty__title">Aucun service</p>
                                <p class="table-empty__text">Ajoutez votre premier service</p>
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
