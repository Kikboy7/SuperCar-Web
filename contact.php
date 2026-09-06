<?php
/**
 * contact.php - Formulaire de contact.
 * Affiche les coordonnees de l'entreprise et enregistre le message
 * du visiteur dans la table `message`.
 */
$pageTitle = "Contact";
include 'includes/header.php';

$client = null;
if (isset($_SESSION['client'])) {
    $stmt = $pdo->prepare("SELECT nom, email FROM client WHERE id_client = ?");
    $stmt->execute([$_SESSION['client']]);
    $client = $stmt->fetch();
}

$success = "";
$error = "";
$saisie = ['nom' => '', 'email' => ''];

// Pre-remplir le formulaire si le visiteur est connecte.
if ($client) {
    $saisie = ['nom' => $client['nom'], 'email' => $client['email']];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom    = trim($_POST['nom'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $contenu = trim($_POST['message'] ?? '');
    $id_client = $client ? $_SESSION['client'] : null;

    if (!csrf_verify()) {
        $error = "Session de securite invalide, veuillez reessayer.";
    } elseif ($nom === '' || $email === '' || $contenu === '') {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse e-mail n'est pas valide.";
    } else {
        $saisie = ['nom' => $nom, 'email' => $email];

        $stmt = $pdo->prepare("
            INSERT INTO message (nom, email, message, date_message, id_client, statut_message)
            VALUES (?, ?, ?, NOW(), ?, 'nouveau')
        ");
        $stmt->execute([$nom, $email, $contenu, $id_client]);

        $success = "Votre message a bien &eacute;t&eacute; envoy&eacute;. Notre &eacute;quipe vous r&eacute;pondra rapidement.";
        $saisie = ['nom' => '', 'email' => ''];
    }
}
?>

<section class="page-hero">
    <div class="container">
        <span class="section-tag">Contact</span>
        <h1>Un service premium commence par une vraie &eacute;coute</h1>
        <p>Une question sur un v&eacute;hicule, un essai ou nos services&nbsp;? Envoyez-nous un message, nous vous r&eacute;pondrons rapidement.</p>
    </div>
</section>

<div class="container section">

    <div class="contact-grid">

        <!-- Coordonnees -->
        <div>
            <div class="section-head" style="text-align:left; margin-bottom:30px;">
                <span class="section-tag">Coordonn&eacute;es</span>
                <h2 class="section-title">Nous trouver</h2>
            </div>

            <div class="info-card">
                <span class="icon">&#128205;</span>
                <div>
                    <h3>Adresse</h3>
                    <p>SuperCar &mdash; Port-Louis, &Icirc;le Maurice</p>
                </div>
            </div>

            <div class="info-card">
                <span class="icon">&#128222;</span>
                <div>
                    <h3>T&eacute;l&eacute;phone</h3>
                    <p>+230 212 0000 &nbsp; | &nbsp; +230 5250 0000</p>
                </div>
            </div>

            <div class="info-card">
                <span class="icon">&#9993;</span>
                <div>
                    <h3>E-mail</h3>
                    <p>contact@supercar.mu</p>
                </div>
            </div>

            <div class="info-card">
                <span class="icon">&#9203;</span>
                <div>
                    <h3>Horaires</h3>
                    <p>Lun - Ven : 09h00 - 17h00 &nbsp; | &nbsp; Sam : 09h00 - 13h00</p>
                </div>
            </div>

            <iframe class="map-frame" src="https://maps.google.com/maps?q=Port%20Louis%20Mauritius&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
        </div>

        <!-- Formulaire -->
        <div class="form-card">
            <h2 class="form-title">Envoyer une demande</h2>
            <p class="form-intro">Une question, une demande d'information&nbsp;? &Eacute;crivez-nous.</p>

            <?php if ($success) { ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php } ?>

            <?php if ($error) { ?>
                <div class="alert alert-error"><?php echo e($error); ?></div>
            <?php } ?>

            <form method="POST">
                <?php echo csrf_field(); ?>

                <input type="text" name="nom" placeholder="Votre nom complet" value="<?php echo e($saisie['nom']); ?>" required>
                <input type="email" name="email" placeholder="Votre adresse e-mail" value="<?php echo e($saisie['email']); ?>" required>
                <textarea name="message" placeholder="D&eacute;crivez votre demande..." required></textarea>

                <button type="submit" class="btn btn-main btn-block">Envoyer le message</button>
            </form>

            <p class="form-note">Votre demande sera enregistr&eacute;e afin que l'&eacute;quipe SuperCar puisse la consulter.</p>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>