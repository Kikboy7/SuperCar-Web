<?php
/**
 * login.php - Connexion d'un client.
 * Verifie l'identifiant et le mot de passe (hache en base avec password_verify),
 * puis enregistre l'id du client en session.
 */
$pageTitle = "Connexion";
include 'includes/header.php';

$message = "";
$saisie_identifiant = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Protection CSRF : on verifie que le formulaire vient bien du navigateur.
    if (!csrf_verify()) {
        $message = "Session de securite invalide, veuillez reessayer.";
    } else {
        $identifiant = trim($_POST['identifiant'] ?? '');
        $password    = $_POST['password'] ?? '';
        $saisie_identifiant = $identifiant;

        // On cherche le compte login et le client associe.
        $stmt = $pdo->prepare("
            SELECT l.*, c.nom
            FROM login l
            JOIN client c ON l.id_client = c.id_client
            WHERE l.identifiant = ?
        ");
        $stmt->execute([$identifiant]);
        $user = $stmt->fetch();

        // password_verify compare le mot de passe saisi au hash stocke en base.
        if ($user && password_verify($password, $user['mot_de_passe'])) {

            // On change l'identifiant de session pour eviter la fixation de session.
            session_regenerate_id(true);

            // On enregistre l'id du client pour le reconnaitre sur les autres pages.
            $_SESSION['client'] = $user['id_client'];

            header("Location: index.php");
            exit();
        } else {
            $message = "Identifiant ou mot de passe incorrect.";
        }
    }
}
?>

<div class="auth-wrap">
    <div class="auth-box">
        <div class="form-card">
            <h2>Connexion</h2>
            <p class="auth-sub">Ravi de vous revoir&nbsp;!</p>

            <?php if ($message) { ?>
                <div class="alert alert-error"><?php echo e($message); ?></div>
            <?php } ?>

            <form method="POST">
                <?php echo csrf_field(); ?>

                <input type="text" name="identifiant" placeholder="Identifiant" value="<?php echo e($saisie_identifiant); ?>" required>
                <input type="password" name="password" placeholder="Mot de passe" required>

                <button type="submit" class="btn btn-main btn-block">Se connecter</button>
            </form>

            <p class="form-note">
                Pas encore de compte&nbsp;? <a href="register.php">Cr&eacute;er un compte</a>
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>