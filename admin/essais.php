<?php
include 'includes/auth.php';

$pageTitle = "Demandes d'essai";
$message = "";

// Changement de statut d'une demande.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!csrf_verify()) {
        $message = "Session de securite invalide.";
    } else {
        $id_essai = $_POST['id_essai'];
        $statut = $_POST['statut'];

        $stmt = $pdo->prepare("UPDATE essai SET statut = ? WHERE id_essai = ?");
        $stmt->execute([$statut, $id_essai]);

        $message = "Statut mis a jour.";
    }
}

// On recupere les demandes avec les informations du client et de la voiture.
$stmt = $pdo->query("
    SELECT e.*, c.nom, c.email, c.telephone, v.modele, m.nom_marque
    FROM essai e
    JOIN client c ON e.id_client = c.id_client
    JOIN voiture v ON e.id_voiture = v.id_voiture
    JOIN marque m ON v.id_marque = m.id_marque
    ORDER BY e.date_demande DESC
");
$essais = $stmt->fetchAll();

include 'includes/header.php';
?>

<?php if ($message) { ?>
    <p class="message"><?php echo htmlspecialchars($message); ?></p>
<?php } ?>

<div class="card">
    <table>
        <tr>
            <th>Client</th>
            <th>Contact</th>
            <th>Voiture</th>
            <th>Date essai</th>
            <th>Heure</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>

        <?php foreach ($essais as $essai) { ?>
            <tr>
                <td><?php echo htmlspecialchars($essai['nom']); ?></td>
                <td>
                    <?php echo htmlspecialchars($essai['email']); ?><br>
                    <?php echo htmlspecialchars($essai['telephone']); ?>
                </td>
                <td><?php echo htmlspecialchars($essai['nom_marque'] . " " . $essai['modele']); ?></td>
                <td><?php echo htmlspecialchars($essai['date_essai']); ?></td>
                <td><?php echo $essai['heure_essai'] ? htmlspecialchars(substr($essai['heure_essai'], 0, 5)) : 'Non renseignee'; ?></td>
                <td class="status"><?php echo htmlspecialchars($essai['statut']); ?></td>
                <td>
                    <form method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id_essai" value="<?php echo $essai['id_essai']; ?>">
                        <select name="statut">
                            <option value="en attente" <?php if ($essai['statut'] == 'en attente') echo 'selected'; ?>>en attente</option>
                            <option value="valide" <?php if ($essai['statut'] == 'valide') echo 'selected'; ?>>valide</option>
                            <option value="refuse" <?php if ($essai['statut'] == 'refuse') echo 'selected'; ?>>refuse</option>
                        </select>
                        <button class="btn" type="submit">Modifier</button>
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
