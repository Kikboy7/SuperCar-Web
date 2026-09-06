<?php
include 'includes/auth.php';

$pageTitle = "Messages";
$messageInfo = "";

// Suppression d'un message (formulaire POST protege par CSRF).
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supprimer_message'])) {
    if (!csrf_verify()) {
        $messageInfo = "Session de securite invalide.";
    } else {
        $id_message = (int) $_POST['supprimer_message'];

        $stmt = $pdo->prepare("DELETE FROM message WHERE id_message = ?");
        $stmt->execute([$id_message]);

        $messageInfo = "Message supprime.";
    }
}

// Reponse ou changement de statut d'un message.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_message'])) {
    if (!csrf_verify()) {
        $messageInfo = "Session de securite invalide.";
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

        $messageInfo = "Message mis a jour.";
    }
}

// On recupere les messages avec le client associe si le message vient d'un compte connecte.
$stmt = $pdo->query("
    SELECT message.*, client.id_client, client.telephone, client.adresse
    FROM message
    LEFT JOIN client ON message.id_client = client.id_client
    ORDER BY message.date_message DESC
");
$messages = $stmt->fetchAll();

include 'includes/header.php';
?>

<?php if ($messageInfo) { ?>
    <p class="message"><?php echo htmlspecialchars($messageInfo); ?></p>
<?php } ?>

<div class="card">
    <h2>Messages clients</h2>

    <table>
        <tr>
            <th>Expediteur</th>
            <th>Message</th>
            <th>Date</th>
            <th>Statut / Reponse</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($messages as $msg) { ?>
            <tr>
                <td>
                    <strong><?php echo htmlspecialchars($msg['nom']); ?></strong><br>
                    <?php echo htmlspecialchars($msg['email']); ?><br>

                    <?php if ($msg['id_client']) { ?>
                        <span class="badge badge-client">Client connecte</span><br>
                        Tel : <?php echo htmlspecialchars($msg['telephone'] ?? 'Non renseigne'); ?><br>
                        Adresse : <?php echo htmlspecialchars($msg['adresse'] ?? 'Non renseignee'); ?>
                    <?php } else { ?>
                        <span class="badge badge-visiteur">Visiteur</span>
                    <?php } ?>
                </td>

                <td><?php echo nl2br(htmlspecialchars($msg['message'])); ?></td>

                <td><?php echo htmlspecialchars($msg['date_message']); ?></td>

                <td>
                    <form method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id_message" value="<?php echo $msg['id_message']; ?>">

                        <select name="statut_message">
                            <option value="nouveau" <?php if ($msg['statut_message'] == 'nouveau') echo 'selected'; ?>>nouveau</option>
                            <option value="en cours" <?php if ($msg['statut_message'] == 'en cours') echo 'selected'; ?>>en cours</option>
                            <option value="traite" <?php if ($msg['statut_message'] == 'traite') echo 'selected'; ?>>traite</option>
                        </select>

                        <textarea name="reponse_admin" placeholder="Reponse ou note interne"><?php echo htmlspecialchars($msg['reponse_admin'] ?? ''); ?></textarea>

                        <?php if (!empty($msg['date_reponse'])) { ?>
                            <small>Derniere reponse : <?php echo htmlspecialchars($msg['date_reponse']); ?></small>
                        <?php } ?>

                        <button class="btn" type="submit">Enregistrer</button>
                    </form>
                </td>

                <td>
                    <a class="btn" href="mailto:<?php echo htmlspecialchars($msg['email']); ?>">
                        Repondre par email
                    </a>

                    <a class="btn btn-danger" href="javascript:void(0)"
                       onclick="document.getElementById('del-<?php echo $msg['id_message']; ?>').submit(); return false;">
                        Supprimer
                    </a>

                    <form method="POST" id="del-<?php echo $msg['id_message']; ?>" style="display:none;">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="supprimer_message" value="<?php echo $msg['id_message']; ?>">
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
