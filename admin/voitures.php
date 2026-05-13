<?php
include 'includes/auth.php';

$pageTitle = "Voitures";
$message = "";

// Ajouter ou modifier une voiture.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_voiture = $_POST['id_voiture'] ?? "";
    $modele = trim($_POST['modele']);
    $prix = $_POST['prix'];
    $description = trim($_POST['description']);
    $image = trim($_POST['image']);
    $id_marque = $_POST['id_marque'];

    if ($id_voiture) {
        $stmt = $pdo->prepare("
            UPDATE voiture
            SET modele = ?, prix = ?, description = ?, image = ?, id_marque = ?
            WHERE id_voiture = ?
        ");
        $stmt->execute([$modele, $prix, $description, $image, $id_marque, $id_voiture]);
        $message = "Voiture modifiee.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO voiture (modele, prix, description, image, id_marque)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$modele, $prix, $description, $image, $id_marque]);
        $message = "Voiture ajoutee.";
    }
}

// Supprimer une voiture.
if (isset($_GET['delete'])) {
    $id_voiture = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM voiture_image WHERE id_voiture = ?");
    $stmt->execute([$id_voiture]);

    $stmt = $pdo->prepare("DELETE FROM voiture WHERE id_voiture = ?");
    $stmt->execute([$id_voiture]);

    $message = "Voiture supprimee.";
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
        <input type="hidden" name="id_voiture" value="<?php echo $voitureEdit['id_voiture'] ?? ''; ?>">

        <div class="form-grid">
            <div>
                <label>Modele</label>
                <input type="text" name="modele" value="<?php echo htmlspecialchars($voitureEdit['modele'] ?? ''); ?>" required>
            </div>

            <div>
                <label>Prix</label>
                <input type="number" name="prix" value="<?php echo htmlspecialchars($voitureEdit['prix'] ?? ''); ?>" required>
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
                    <a class="btn" href="voitures.php?edit=<?php echo $voiture['id_voiture']; ?>">Modifier</a>
                    <a class="btn btn-danger" href="voitures.php?delete=<?php echo $voiture['id_voiture']; ?>"
                       onclick="return confirm('Supprimer cette voiture ?');">
                        Supprimer
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

    </main>
</div>

</body>
</html>
