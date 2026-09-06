<?php
include 'includes/auth.php';

$pageTitle = "Voitures";
$message = "";

// Ajouter ou modifier une voiture.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modele'])) {
    if (!csrf_verify()) {
        $message = "Session de securite invalide.";
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
            $message = "Voiture modifiee.";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO voiture (modele, prix, description, image, id_marque, annee, carburant, boite, puissance)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$modele, $prix, $description, $image, $id_marque, $annee, $carburant, $boite, $puissance]);
            $message = "Voiture ajoutee.";
        }
    }
}

// Supprimer une voiture (formulaire POST protege par CSRF).
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supprimer_voiture'])) {
    if (!csrf_verify()) {
        $message = "Session de securite invalide.";
    } else {
        $id_voiture = (int) $_POST['supprimer_voiture'];

        // On supprime d'abord les images liees puis la voiture (contrainte de cle etrangere).
        $stmt = $pdo->prepare("DELETE FROM voiture_image WHERE id_voiture = ?");
        $stmt->execute([$id_voiture]);

        $stmt = $pdo->prepare("DELETE FROM voiture WHERE id_voiture = ?");
        $stmt->execute([$id_voiture]);

        $message = "Voiture supprimee.";
    }
}

// Recuperer une voiture pour la modifier.
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

<?php if ($message) { ?>
    <p class="message"><?php echo htmlspecialchars($message); ?></p>
<?php } ?>

<div class="card">
    <h2><?php echo $voitureEdit ? "Modifier une voiture" : "Ajouter une voiture"; ?></h2>

    <form method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id_voiture" value="<?php echo htmlspecialchars($voitureEdit['id_voiture'] ?? ''); ?>">

        <div class="form-grid">
            <div>
                <label>Modele</label>
                <input type="text" name="modele" value="<?php echo htmlspecialchars($voitureEdit['modele'] ?? ''); ?>" required>
            </div>

            <div>
                <label>Prix (Rs)</label>
                <input type="number" name="prix" min="0" value="<?php echo htmlspecialchars($voitureEdit['prix'] ?? ''); ?>" required>
            </div>

            <div>
                <label>Marque</label>
                <select name="id_marque" required>
                    <?php foreach ($marques as $marque) { ?>
                        <option value="<?php echo $marque['id_marque']; ?>"
                            <?php if (($voitureEdit['id_marque'] ?? '') == $marque['id_marque']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($marque['nom_marque']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label>Image principale</label>
                <input type="text" name="image" placeholder="exemple.jpg"
                       value="<?php echo htmlspecialchars($voitureEdit['image'] ?? ''); ?>">
            </div>

            <div>
                <label>Ann&eacute;e</label>
                <input type="number" name="annee" min="1980" max="2030" placeholder="2024"
                       value="<?php echo htmlspecialchars($voitureEdit['annee'] ?? ''); ?>">
            </div>

            <div>
                <label>Puissance (ch)</label>
                <input type="number" name="puissance" min="0" placeholder="510"
                       value="<?php echo htmlspecialchars($voitureEdit['puissance'] ?? ''); ?>">
            </div>

            <div>
                <label>Carburant</label>
                <select name="carburant">
                    <option value="">-- Choisir --</option>
                    <?php foreach (['Essence', 'Diesel', 'Hybride', 'Electrique'] as $c) { ?>
                        <option value="<?php echo $c; ?>" <?php if (($voitureEdit['carburant'] ?? '') == $c) echo 'selected'; ?>>
                            <?php echo $c; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label>Bo&icirc;te de vitesses</label>
                <select name="boite">
                    <option value="">-- Choisir --</option>
                    <?php foreach (['Automatique', 'Manuelle'] as $b) { ?>
                        <option value="<?php echo $b; ?>" <?php if (($voitureEdit['boite'] ?? '') == $b) echo 'selected'; ?>>
                            <?php echo $b; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <label>Description</label>
        <textarea name="description"><?php echo htmlspecialchars($voitureEdit['description'] ?? ''); ?></textarea>

        <button class="btn" type="submit"><?php echo $voitureEdit ? "Modifier" : "Ajouter"; ?></button>
        <?php if ($voitureEdit) { ?>
            <a href="voitures.php" class="btn btn-light">Annuler</a>
        <?php } ?>
    </form>
</div>

<div class="card">
    <h2>Liste des voitures</h2>

    <table>
        <tr>
            <th>Image</th>
            <th>Marque</th>
            <th>Modele</th>
            <th>Prix</th>
            <th>Caracteristiques</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($voitures as $voiture) { ?>
            <tr>
                <td>
                    <?php if ($voiture['image']) { ?>
                        <img src="../images/<?php echo htmlspecialchars($voiture['image']); ?>" width="90">
                    <?php } ?>
                </td>
                <td><?php echo htmlspecialchars($voiture['nom_marque']); ?></td>
                <td><?php echo htmlspecialchars($voiture['modele']); ?></td>
                <td>Rs <?php echo number_format($voiture['prix'], 0, ',', ' '); ?></td>
                <td>
                    <?php if ($voiture['annee']) { ?><?php echo htmlspecialchars($voiture['annee']); ?><br><?php } ?>
                    <?php if ($voiture['puissance']) { ?><?php echo htmlspecialchars($voiture['puissance']); ?> ch<br><?php } ?>
                    <?php echo htmlspecialchars($voiture['carburant'] ?? ''); ?>
                    <?php if ($voiture['boite']) { ?>&middot; <?php echo htmlspecialchars($voiture['boite']); ?><?php } ?>
                </td>
                <td>
                    <a class="btn" href="voitures.php?edit=<?php echo $voiture['id_voiture']; ?>">Modifier</a>

                    <form method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="supprimer_voiture" value="<?php echo $voiture['id_voiture']; ?>">
                        <button class="btn btn-danger" type="submit"
                                onclick="return confirm('Supprimer cette voiture ?');">
                            Supprimer
                        </button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

    </main>
</div>

</body>
</html>