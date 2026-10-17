<?php
/**
 * register.php - Creation d'un compte client.
 * Cree une fiche dans la table client puis un compte dans la table login
 * (mot de passe hache avec password_hash). Les deux insertions se font dans
 * une transaction pour qu'elles reussissent ensemble.
 */
$pageTitle = "Inscription";
require_once __DIR__ . '/includes/init.php';

$message = "";
$success = false;
$saisie = ['nom' => '', 'email' => '', 'identifiant' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!csrf_verify()) {
        $message = "Session de sécurité invalide, veuillez réessayer.";
    } else {
        $nom = saisie('nom');
        $email = saisie('email');
        $identifiant = saisie('identifiant');
        $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
        $confirm = is_string($_POST['confirm'] ?? null) ? $_POST['confirm'] : '';

        $saisie = ['nom' => $nom, 'email' => $email, 'identifiant' => $identifiant];

        if (empty($nom) || empty($email) || empty($identifiant) || empty($password)) {
            $message = "Veuillez remplir tous les champs.";
        } elseif (mb_strlen($nom) > 100 || mb_strlen($identifiant) > 100 || mb_strlen($email) > 150) {
            $message = "Le nom et l’identifiant sont limités à 100 caractères, et l’e-mail à 150 caractères.";
        } elseif (!mb_check_encoding($password, 'UTF-8') || str_contains($password, "\0") || mb_strlen($password) < 12 || strlen($password) > 72) {
            $message = "Choisissez un mot de passe d’au moins 12 caractères (72 octets maximum).";
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
                $message = "Cet identifiant ou cet e-mail est déjà utilisé.";
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

                    $_SESSION['compte_cree'] = true;
                    header('Location: login.php', true, 303);
                    exit();
                } catch (Exception $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $message = "Erreur lors de la création du compte.";
                }
            }
        }
    }
}
include 'includes/header.php';
?>

<div class="auth-wrap">
    <div class="auth-box">
        <div class="form-card">
            <h1 class="form-title">Cr&eacute;er un compte</h1>
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

                    <label for="nom">Nom complet</label><input id="nom" type="text" name="nom" maxlength="100" autocomplete="name" value="<?php echo e($saisie['nom']); ?>" required>
                    <label for="email">Adresse e-mail</label><input id="email" type="email" name="email" maxlength="150" autocomplete="email" value="<?php echo e($saisie['email']); ?>" required>
                    <label for="identifiant">Identifiant</label><input id="identifiant" type="text" name="identifiant" maxlength="100" autocomplete="username" value="<?php echo e($saisie['identifiant']); ?>" required>
                    <label for="password">Mot de passe</label><input id="password" type="password" name="password" minlength="12" maxlength="72" autocomplete="new-password" aria-describedby="password-aide" required>
                    <p id="password-aide" class="muted">Au moins 12 caractères.</p>
                    <label for="confirm">Confirmer le mot de passe</label><input id="confirm" type="password" name="confirm" minlength="12" maxlength="72" autocomplete="new-password" required>

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
