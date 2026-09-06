<?php
include 'includes/auth.php';

$pageTitle = "Services";
$message = "";

// Ajouter ou modifier un service.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom_services'])) {
    if (!csrf_verify()) {
        $message = "Session de securite invalide.";
    } else {
        $id_services = $_POST['id_services'] ?? "";
        $nom = trim($_POST['nom_services']);
        $description = trim($_POST['description_services']);
        $prix = $_POST['prix_services'];

        if ($id_services) {
            $stmt = $pdo->prepare("
                UPDATE services
                SET nom_services = ?, description_services = ?, prix_services = ?
                WHERE id_services = ?
            ");
            $stmt->execute([$nom, $description, $prix, $id_services]);
            $message = "Service modifie.";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO services (nom_services, description_services, prix_services)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$nom, $description, $prix]);
            $message = "Service ajoute.";
        }
    }
}

// Supprimer un service (formulaire POST protege par CSRF).
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supprimer_service'])) {
    if (!csrf_verify()) {
        $message = "Session de securite invalide.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id_services = ?");
        $stmt->execute([(int) $_POST['supprimer_service']]);
        $message = "Service supprime.";
    }
}

// Si on clique sur modifier, on recupere le service a afficher dans le formulaire.
$serviceEdit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id_services = ?");
    $stmt->execute([$_GET['edit']]);
    $serviceEdit = $stmt->fetch();
}

$services = $pdo->query("SELECT * FROM services ORDER BY id_services")->fetchAll();

include 'includes/header.php';
?>

<?php if ($message) { ?>
    <p class="message"><?php echo htmlspecialchars($message); ?></p>
<?php } ?>

<div class="card">
    <h2><?php echo $serviceEdit ? "Modifier un service" : "Ajouter un service"; ?></h2>

    <form method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id_services" value="<?php echo $serviceEdit['id_services'] ?? ''; ?>">

        <input type="text" name="nom_services" placeholder="Nom du service"
               value="<?php echo htmlspecialchars($serviceEdit['nom_services'] ?? ''); ?>" required>

        <textarea name="description_services" placeholder="Description"><?php echo htmlspecialchars($serviceEdit['description_services'] ?? ''); ?></textarea>

        <input type="number" name="prix_services" placeholder="Prix"
               value="<?php echo htmlspecialchars($serviceEdit['prix_services'] ?? '0'); ?>" required>

        <button class="btn" type="submit"><?php echo $serviceEdit ? "Modifier" : "Ajouter"; ?></button>
        <?php if ($serviceEdit) { ?>
            <a href="services.php" class="btn btn-light">Annuler</a>
        <?php } ?>
    </form>
</div>

<div class="card">
    <h2>Liste des services</h2>

    <table>
        <tr>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($services as $service) { ?>
            <tr>
                <td><?php echo htmlspecialchars($service['nom_services']); ?></td>
                <td><?php echo htmlspecialchars($service['description_services']); ?></td>
                <td>Rs <?php echo number_format($service['prix_services'], 0, ',', ' '); ?></td>
                <td>
                    <a class="btn" href="services.php?edit=<?php echo $service['id_services']; ?>">Modifier</a>

                    <form method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="supprimer_service" value="<?php echo $service['id_services']; ?>">
                        <button class="btn btn-danger" type="submit"
                                onclick="return confirm('Supprimer ce service ?');">
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
