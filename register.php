<?php
/**
 * register.php - Creation d'un compte client.
 * Cree une fiche dans la table client puis un compte dans la table login
 * (mot de passe hache avec password_hash). Les deux insertions se font dans
 * une transaction pour qu'elles reussissent ensemble.
 */
$pageTitle = "Inscription";
include 'includes/header.php';

$message = "";
$success = false;
$saisie = ['nom' => '', 'email' => '', 'identifiant' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!csrf_verify()) {
        $message = "Session de securite invalide, veuillez reessayer.";
    } else {
        $nom        = trim($_POST['nom'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $identifiant = trim($_POST['identifiant'] ?? '');
        $password   = $_POST['password'] ?? '';
        $confirm    = $_POST['confirm'] ?? '';

        $saisie = ['nom' => $nom, 'email' => $email, 'identifiant' => $identifiant];

        if (empty($nom) || empty($email) || empty($identifiant) || empty($password)) {
            $message = "Veuillez remplir tous les champs.";
        } elseif ($password !== $confirm) {
            $message = "Les mots de passe ne correspondent pas.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "L'adresse e-mail n'est pas valide.";
        } else {
            // On verifie que l'identifiant ou l'email n'existe pas deja.
            $stmt = $pdo->prepare("
                SELECT l.id_login
                FROM login l
                JOIN client c ON l.id_client = c.id_client
                WHERE l.identifiant = ? OR c.email = ?
            ");
            $stmt->execute([$identifiant, $email]);

            if ($stmt->fetch()) {
                $message = "Cet identifiant ou cet e-mail est d&eacute;j&agrave; utilis&eacute;.";
            } else {
                try {
                    // Le mot de passe est hache avant d'etre enregistre.
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);

                    // Transaction : les deux insertions doivent reussir ensemble.
                    $pdo->beginTransaction();

                    // 1. Creation de la fiche client.
                    $stmt = $pdo->prepare("INSERT INTO client (nom, email) VALUES (?, ?)");
                    $stmt->execute([$nom, $email]);
                    $id_client = $pdo->lastInsertId();

                    // 2. Creation du login lie au client.
                    $stmt = $pdo->prepare("
                        INSERT INTO login (identifiant, mot_de_passe, id_client)
                        VALUES (?, ?, ?)
                    ");
                    $stmt->execute([$identifiant, $password_hash, $id_client]);

                    $pdo->commit();

                    $success = true;
                } catch (Exception $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $message = "Erreur lors de la cr&eacute;ation du compte.";
                }
            }
        }
    }
}
?>

<div class="auth-wrap">
    <div class="auth-box">
        <div class="form-card">
            <h2>Cr&eacute;er un compte</h2>
            <p class="auth-sub">Rejoignez SuperCar en quelques secondes.</p>

            <?php if ($message) { ?>
                <div class="alert alert-error"><?php echo $message; ?></div>
            <?php } ?>

            <?php if ($success) { ?>
                <div class="alert alert-success">
                    Compte cr&eacute;&eacute; avec succ&egrave;s&nbsp;! Vous pouvez maintenant
                    <a href="login.php">vous connecter</a>.
                </div>
            <?php } ?>

            <?php if (!$success) { ?>
                <form method="POST">
                    <?php echo csrf_field(); ?>

                    <input type="text" name="nom" placeholder="Nom complet" value="<?php echo e($saisie['nom']); ?>" required>
                    <input type="email" name="email" placeholder="Adresse e-mail" value="<?php echo e($saisie['email']); ?>" required>
                    <input type="text" name="identifiant" placeholder="Identifiant" value="<?php echo e($saisie['identifiant']); ?>" required>
                    <input type="password" name="password" placeholder="Mot de passe" required>
                    <input type="password" name="confirm" placeholder="Confirmer le mot de passe" required>

                    <button type="submit" class="btn btn-main btn-block">S'inscrire</button>
                </form>

                <p class="form-note">
                    D&eacute;j&agrave; un compte&nbsp;? <a href="login.php">Se connecter</a>
                </p>
            <?php } ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>