<?php
/**
 * demande_essai.php - Demande d'essai d'une voiture.
 * Un client connecte choisit une voiture, une date et un horaire,
 * puis ses informations sont mises a jour et la demande est enregistree.
 *
 * Les deux operations (mise a jour du client + creation de l'essai) sont
 * realisees dans une transaction afin de garder une coherence parfaite.
 */
$pageTitle = "Demande d'essai";
include 'includes/header.php';

// Il faut etre connecte pour faire une demande d'essai.
if (!isset($_SESSION['client'])) {
    header("Location: login.php");
    exit();
}

$id_client = $_SESSION['client'];
$message = "";
$error = "";
$success = false;

// Voiture preselectionnee depuis l'URL (exemple : detail.php?id=3).
$id_voiture_preselection = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int) $_GET['id'] : 0;

// Voiture actuellement selectionnee dans le formulaire (par defaut celle de l'URL).
$id_voiture_prenue = $id_voiture_preselection;

// Horaires disponibles pour un essai (08:00 a 18:00).
$heures_disponibles = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];

// Liste des voitures pour le menu deroulant.
$voitures = $pdo->query("
    SELECT voiture.id_voiture, voiture.modele, marque.nom_marque
    FROM voiture
    JOIN marque ON voiture.id_marque = marque.id_marque
    ORDER BY marque.nom_marque, voiture.modele
")->fetchAll();

// Informations actuelles du client pour pre-remplir le formulaire.
$stmt = $pdo->prepare("SELECT nom, email, telephone, adresse FROM client WHERE id_client = ?");
$stmt->execute([$id_client]);
$client_infos = $stmt->fetch();

if (!$client_infos) {
    unset($_SESSION['client']);
    header("Location: login.php");
    exit();
}

// Date minimale autorisee : aujourd'hui (format YYYY-MM-DD pour l'attribut min).
$date_min = date('Y-m-d');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_voiture_post = (int) ($_POST['id_voiture'] ?? 0);
    $nom      = trim($_POST['nom'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $adresse  = trim($_POST['adresse'] ?? '');
    $date     = $_POST['date'] ?? '';
    $heure    = $_POST['heure'] ?? '';

    if (!csrf_verify()) {
        $error = "Session de securite invalide, veuillez reessayer.";
    } elseif ($nom === '' || $email === '' || $adresse === '' || $date === '' || $heure === '' || $id_voiture_post <= 0) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } elseif ($date < $date_min) {
        $error = "La date de l'essai doit etre aujourd'hui ou dans le futur.";
    } elseif (!in_array($heure, $heures_disponibles)) {
        $error = "Veuillez choisir une heure valide entre 08:00 et 18:00.";
    } else {
        // On verifie que la voiture choisie existe reellement.
        $stmt = $pdo->prepare("SELECT id_voiture FROM voiture WHERE id_voiture = ?");
        $stmt->execute([$id_voiture_post]);
        if (!$stmt->fetch()) {
            $error = "La voiture selectionnee n'existe pas.";
        } else {
            try {
                $pdo->beginTransaction();

                // 1. Mise a jour des informations du profil client connecte.
                $stmt = $pdo->prepare("
                    UPDATE client
                    SET nom = ?, email = ?, telephone = ?, adresse = ?
                    WHERE id_client = ?
                ");
                $stmt->execute([$nom, $email, $telephone, $adresse, $id_client]);

                // 2. Creation de la demande d'essai.
                $stmt = $pdo->prepare("
                    INSERT INTO essai (date_essai, heure_essai, statut, id_client, id_voiture)
                    VALUES (?, ?, 'en attente', ?, ?)
                ");
                $stmt->execute([$date, $heure, $id_client, $id_voiture_post]);

                $pdo->commit();

                $success = true;
                $message = "Votre demande d'essai a bien ete envoyee.";
                $id_voiture_prenue = $id_voiture_post;
                $client_infos = ['nom' => $nom, 'email' => $email, 'telephone' => $telephone, 'adresse' => $adresse];
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $error = "Erreur lors de l'envoi de la demande.";
            }
        }
    }
}
?>

<div class="container section">
    <div class="form-card wide">
        <h1 class="form-title">Demander un essai</h1>
        <p class="form-intro">Choisissez une voiture, une date et un horaire disponible.</p>

        <?php if ($success) { ?>
            <div class="alert alert-success"><?php echo e($message); ?></div>
            <div class="text-center">
                <a href="compte.php#tab-reservations" class="btn btn-main">Voir mes r&eacute;servations</a>
                <a href="voitures.php" class="btn btn-outline">Retour au catalogue</a>
            </div>
        <?php } ?>

        <?php if (!$success) { ?>
            <?php if ($error) { ?>
                <div class="alert alert-error"><?php echo e($error); ?></div>
            <?php } ?>

            <form method="POST">
                <?php echo csrf_field(); ?>

                <select name="id_voiture" required>
                    <option value="">Choisir une voiture</option>
                    <?php foreach ($voitures as $voiture) { ?>
                        <option value="<?php echo $voiture['id_voiture']; ?>"
                            <?php echo $id_voiture_prenue == $voiture['id_voiture'] ? 'selected' : ''; ?>>
                            <?php echo e($voiture['nom_marque'] . ' ' . $voiture['modele']); ?>
                        </option>
                    <?php } ?>
                </select>

                <div class="form-row">
                    <input type="date" name="date" min="<?php echo $date_min; ?>" required>
                    <select name="heure" required>
                        <option value="">Choisir une heure</option>
                        <?php foreach ($heures_disponibles as $heure) { ?>
                            <option value="<?php echo $heure; ?>"><?php echo $heure; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <input type="text" name="nom" placeholder="Nom complet" value="<?php echo e($client_infos['nom'] ?? ''); ?>" required>
                <input type="email" name="email" placeholder="Adresse e-mail" value="<?php echo e($client_infos['email'] ?? ''); ?>" required>
                <input type="text" name="telephone" placeholder="T&eacute;l&eacute;phone" value="<?php echo e($client_infos['telephone'] ?? ''); ?>">
                <input type="text" name="adresse" placeholder="Adresse" value="<?php echo e($client_infos['adresse'] ?? ''); ?>" required>

                <button type="submit" class="btn btn-main btn-block">Envoyer la demande</button>
            </form>
        <?php } ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>