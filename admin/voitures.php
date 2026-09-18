<?php
include 'includes/auth.php';

$pageTitle = "Voitures";
$message = "";

// Ajouter ou modifier une voiture
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modele'])) {
    if (!csrf_verify()) {
        $message = "<div class=\"alert alert--danger\"><div class=\"alert__content\"><p class=\"alert__text\">Session de sécurité invalide.</p></div></div>";
    } else {
        $id_voiture  = $_POST['id_voiture'] ?? "";
        $modele      = trim($_POST['modele']);
        $prix        = $_POST['prix'];
        $description = trim($_POST['description']);
        $image       = trim($_POST['image']);
        $id_marque   = $_POST['id_marque'];
        $annee       = $_POST['annee'] !== '' ? (int) $_POST['annee'] : null;
        $carburant   = trim($_POST['carburant']);
        $boite       = trim($_POST['boite']);
        $puissance   = $_POST['puissance'] !== '' ? (int) $_POST['puissance'] : null;

        if ($id_voiture) {
            $stmt = $pdo->prepare("
                UPDATE voiture
                SET modele = ?, prix = ?, description = ?, image = ?, id_marque = ?,
                    annee = ?, carburant = ?, boite = ?, puissance = ?
                WHERE id_voiture = ?
            ");
            $stmt->execute([$modele, $prix, $description, $image, $id_marque, $annee, $carburant, $boite, $puissance, $id_voiture]);
            $message = "<div class=\"alert alert--success\"><div class=\"alert__content\"><p class=\"alert__text\">Voiture modifiée.</p></div></div>";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO voiture (modele, prix, description, image, id_marque, annee, carburant, boite, puissance)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$modele, $prix, $description, $image, $id_marque, $annee, $carburant, $boite, $puissance]);
            $message = "<div class=\"alert alert--success\"><div class=\"alert__content\"><p class=\"alert__text\">Voiture ajoutée.</p></div></div>";
        }
    }
}

// Supprimer une voiture
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supprimer_voiture'])) {
    if (!csrf_verify()) {
        $message = "<div class=\"alert alert--danger\"><div class=\"alert__content\"><p class=\"alert__text\">Session de sécurité invalide.</p></div></div>";
    } else {
        $id_voiture = (int) $_POST['supprimer_voiture'];
        $stmt = $pdo->prepare("DELETE FROM voiture_image WHERE id_voiture = ?");
        $stmt->execute([$id_voiture]);
        $stmt = $pdo->prepare("DELETE FROM voiture WHERE id_voiture = ?");
        $stmt->execute([$id_voiture]);
        $message = "<div class=\"alert alert--success\"><div class=\"alert__content\"><p class=\"alert__text\">Voiture supprimée.</p></div></div>";
    }
}

// Récupérer une voiture pour la modifier
$voitureEdit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM voiture WHERE id_voiture = ?");
    $stmt->execute([$_GET['edit']]);
    $voitureEdit = $stmt->fetch();
}

$marques = $pdo->query("SELECT * FROM marque ORDER BY nom_marque")->fetchAll();

$stmt = $pdo->query("
    SELECT v.*, m.nom_marque
    FROM voiture v
    JOIN marque m ON v.id_marque = m.id_marque
    ORDER BY m.nom_marque, v.modele
");
$voitures = $stmt->fetchAll();

include 'includes/header.php';
?>

<?php if ($message) echo $message; ?>

<!-- Formulaire ajout/modification -->
<div class="card">
    <div class="card__header">
        <h2 class="card__title"><?php echo $voitureEdit ? "Modifier une voiture" : "Ajouter une voiture"; ?></h2>
    </div>

    <form method="POST" class="form-grid">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id_voiture" value="<?php echo htmlspecialchars($voitureEdit['id_voiture'] ?? ''); ?>">

        <div class="form-group">
            <label class="form-label form-label--required" for="modele">Modèle</label>
            <input type="text" id="modele" name="modele" class="form-input" value="<?php echo htmlspecialchars($voitureEdit['modele'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label form-label--required" for="prix">Prix (Rs)</label>
            <input type="number" id="prix" name="prix" class="form-input" min="0" value="<?php echo htmlspecialchars($voitureEdit['prix'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label form-label--required" for="id_marque">Marque</label>
            <div class="select-wrapper">
                <select id="id_marque" name="id_marque" class="form-select" required>
                    <?php foreach ($marques as $marque) { ?>
                        <option value="<?php echo $marque['id_marque']; ?>" <?php if (($voitureEdit['id_marque'] ?? '') == $marque['id_marque']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($marque['nom_marque']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="image">Image principale</label>
            <input type="text" id="image" name="image" class="form-input" placeholder="exemple.jpg" value="<?php echo htmlspecialchars($voitureEdit['image'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label class="form-label" for="annee">Année</label>
            <input type="number" id="annee" name="annee" class="form-input" min="1980" max="2030" placeholder="2024" value="<?php echo htmlspecialchars($voitureEdit['annee'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label class="form-label" for="puissance">Puissance (ch)</label>
            <input type="number" id="puissance" name="puissance" class="form-input" min="0" placeholder="510" value="<?php echo htmlspecialchars($voitureEdit['puissance'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label class="form-label" for="carburant">Carburant</label>
            <div class="select-wrapper">
                <select id="carburant" name="carburant" class="form-select">
                    <option value="">-- Choisir --</option>
                    <?php foreach (['Essence', 'Diesel', 'Hybride', 'Electrique'] as $c) { ?>
                        <option value="<?php echo $c; ?>" <?php if (($voitureEdit['carburant'] ?? '') == $c) echo 'selected'; ?>>
                            <?php echo $c; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="boite">Boîte de vitesses</label>
            <div class="select-wrapper">
                <select id="boite" name="boite" class="form-select">
                    <option value="">-- Choisir --</option>
                    <?php foreach (['Automatique', 'Manuelle'] as $b) { ?>
                        <option value="<?php echo $b; ?>" <?php if (($voitureEdit['boite'] ?? '') == $b) echo 'selected'; ?>>
                            <?php echo $b; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group form-group--full">
            <label class="form-label" for="description">Description</label>
            <textarea id="description" name="description" class="form-textarea"><?php echo htmlspecialchars($voitureEdit['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn--primary"><?php echo $voitureEdit ? "Modifier" : "Ajouter"; ?></button>
            <?php if ($voitureEdit) { ?>
                <a href="voitures.php" class="btn btn--secondary">Annuler</a>
            <?php } ?>
        </div>
    </form>
</div>

<!-- Liste des voitures -->
<div class="card">
    <div class="card__header">
        <h2 class="card__title">Liste des voitures</h2>
        <span class="badge badge--neutral"><?php echo count($voitures); ?> voiture(s)</span>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Prix</th>
                    <th>Caractéristiques</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($voitures as $voiture) { ?>
                    <tr>
                        <td>
                            <?php if ($voiture['image']) { ?>
                                <img src="../images/<?php echo htmlspecialchars($voiture['image']); ?>" alt="" class="table-thumb">
                            <?php } else { ?>
                                <span class="text-muted">—</span>
                            <?php } ?>
                        </td>
                        <td><?php echo htmlspecialchars($voiture['nom_marque']); ?></td>
                        <td><strong><?php echo htmlspecialchars($voiture['modele']); ?></strong></td>
                        <td>Rs <?php echo number_format($voiture['prix'], 0, ',', ' '); ?></td>
                        <td>
                            <?php if ($voiture['annee']) { ?><?php echo htmlspecialchars($voiture['annee']); ?><?php } ?>
                            <?php if ($voiture['puissance']) { ?> · <?php echo htmlspecialchars($voiture['puissance']); ?> ch<?php } ?>
                            <?php if ($voiture['carburant']) { ?><br><?php echo htmlspecialchars($voiture['carburant']); ?><?php } ?>
                            <?php if ($voiture['boite']) { ?> · <?php echo htmlspecialchars($voiture['boite']); ?><?php } ?>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a class="btn btn--secondary btn--sm" href="voitures.php?edit=<?php echo $voiture['id_voiture']; ?>">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Modifier
                                </a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette voiture ?');">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="supprimer_voiture" value="<?php echo $voiture['id_voiture']; ?>">
                                    <button type="submit" class="btn btn--danger btn--sm">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        Supprimer
                                    </button>
                                </form>
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