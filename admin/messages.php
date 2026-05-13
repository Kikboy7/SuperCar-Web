<?php
include 'includes/auth.php';

$pageTitle = "Messages";
$messageInfo = "";

// Suppression d'un message.
if (isset($_GET['delete'])) {
    $id_message = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM message WHERE id_message = ?");
    $stmt->execute([$id_message]);

    $messageInfo = "Message supprime.";
}

$stmt = $pdo->query("SELECT * FROM message ORDER BY date_message DESC");
$messages = $stmt->fetchAll();

include 'includes/header.php';
?>

<?php if ($messageInfo) { ?>
    <p class="message"><?php echo htmlspecialchars($messageInfo); ?></p>
<?php } ?>

<div class="card">
    <table>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php foreach ($messages as $msg) { ?>
            <tr>
                <td><?php echo htmlspecialchars($msg['nom']); ?></td>
                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($msg['message'])); ?></td>
                <td><?php echo htmlspecialchars($msg['date_message']); ?></td>
                <td>
                    <a class="btn btn-danger" href="messages.php?delete=<?php echo $msg['id_message']; ?>"
                       onclick="return confirm('Supprimer ce message ?');">
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
