<?php
/**
 * login.php - Connexion d'un client.
 * Verifie l'identifiant et le mot de passe (hache en base avec password_verify),
 * puis enregistre l'id du client en session.
 */
$pageTitle = "Connexion";
require_once __DIR__ . '/includes/init.php';

$message = "";
$saisie_identifiant = "";
$compteCree = !empty($_SESSION['compte_cree']);
unset($_SESSION['compte_cree']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Protection CSRF : on verifie que le formulaire vient bien du navigateur.
    if (!csrf_verify()) {
        $message = "Session de sécurité invalide, veuillez réessayer.";
    } elseif (!connexion_autorisee('client')) {
        http_response_code(429);
        $message = 'Trop de tentatives. Veuillez réessayer dans 15 minutes.';
    } else {
        $identifiant = saisie('identifiant');
        $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
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
        if ($user && strlen($password) <= 72 && password_verify($password, $user['mot_de_passe'])) {

            // On change l'identifiant de session pour eviter la fixation de session.
            session_regenerate_id(true);

            // On enregistre l'id du client pour le reconnaitre sur les autres pages.
            $_SESSION['client'] = $user['id_client'];

            $retour = $_SESSION['retour_connexion'] ?? 'compte.php';
            unset($_SESSION['retour_connexion']);
            header('Location: ' . $retour, true, 303);
            exit();
        } else {
            connexion_autorisee('client', true);
            $message = "Identifiant ou mot de passe incorrect.";
        }
    }
}
include 'includes/header.php';
?>

<div class="auth-wrap">
    <div class="auth-box">
        <div class="form-card">
            <h1 class="form-title">Connexion</h1>
            <p class="auth-sub">Ravi de vous revoir&nbsp;!</p>
            <?php if ($compteCree) { ?><div class="alert alert-success" role="status">Compte créé avec succès ! Vous pouvez maintenant vous connecter.</div><?php } ?>

            <?php if ($message) { ?>
                <div class="alert alert-error"><?php echo e($message); ?></div>
            <?php } ?>

            <form method="POST">
                <?php echo csrf_field(); ?>

                <label for="identifiant">Identifiant</label><input id="identifiant" type="text" name="identifiant" maxlength="100" autocomplete="username" value="<?php echo e($saisie_identifiant); ?>" required>
                <label for="password">Mot de passe</label><input id="password" type="password" name="password" autocomplete="current-password" required>

                <button type="submit" class="btn btn-main btn-block">Se connecter</button>
            </form>

            <p class="form-note">
                Pas encore de compte&nbsp;? <a href="register.php">Cr&eacute;er un compte</a>
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
