<?php
/**
 * demande_essai.php - Demande d'essai d'une voiture.
 * Un client connecte choisit une voiture, une date et un horaire,
 * puis ses informations sont mises a jour et la demande est enregistree.
 *
 * Les deux operations (mise a jour du client + creation de l'essai) sont
 * realisees dans une transaction afin de garder une coherence parfaite.
 */
$pageTitle = "Demande d’essais";
require_once __DIR__ . '/includes/init.php';

// Il faut etre connecte pour faire une demande d'essai.
if (!isset($_SESSION['client'])) {
    $_SESSION['retour_connexion'] = 'demande_essai.php' . (ctype_digit(saisie('id', $_GET)) ? '?id=' . (int) $_GET['id'] : '');
    header("Location: login.php");
    exit();
}

$id_client = $_SESSION['client'];
$message = "";
$error = "";
$success = !empty($_SESSION['essai_envoye']);
unset($_SESSION['essai_envoye']);
if ($success) $message = "Votre demande d’essai a bien été envoyée.";

// Voiture preselectionnee depuis l'URL (exemple : detail.php?id=3).
$id_voiture_preselection = ctype_digit(saisie('id', $_GET)) ? (int) $_GET['id'] : 0;

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
$date = '';
$heureChoisie = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_voiture_post = (int) saisie('id_voiture');
    $nom = saisie('nom');
    $email = saisie('email');
    $telephone = saisie('telephone');
    $adresse = saisie('adresse');
    $date = saisie('date');
    $heure = saisie('heure');
    $heureChoisie = $heure;
    $id_voiture_prenue = $id_voiture_post;
    $client_infos = compact('nom', 'email', 'telephone', 'adresse');
    $dateValide = DateTimeImmutable::createFromFormat('!Y-m-d', $date);

    if (!csrf_verify()) {
        $error = "Session de sécurité invalide, veuillez réessayer.";
    } elseif ($nom === '' || $email === '' || $telephone === '' || $adresse === '' || $date === '' || $heure === '' || $id_voiture_post <= 0) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } elseif (!coordonnees_valides($nom, $telephone, $adresse) || mb_strlen($email) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Vérifiez vos coordonnées : nom (100 caractères), téléphone (20 caractères), adresse (255 caractères) et e-mail valide.";
    } elseif (!$dateValide || $dateValide->format('Y-m-d') !== $date) {
        $error = "Veuillez choisir une date valide.";
    } elseif ($date < $date_min || ($date === $date_min && $heure <= date('H:i'))) {
        $error = "La date de l'essai doit être aujourd'hui ou dans le futur.";
    } elseif (!in_array($heure, $heures_disponibles)) {
        $error = "Veuillez choisir une heure valide entre 08:00 et 18:00.";
    } else {
        // On verifie que la voiture choisie existe reellement.
        $stmt = $pdo->prepare('SELECT id_client FROM client WHERE email = ? AND id_client <> ?');
        $stmt->execute([$email, $id_client]);
        $emailUtilise = (bool) $stmt->fetchColumn();
        $stmt = $pdo->prepare("SELECT id_voiture FROM voiture WHERE id_voiture = ?");
        $stmt->execute([$id_voiture_post]);
        if (!$stmt->fetch()) {
            $error = "La voiture sélectionnée n'existe pas.";
        } elseif ($emailUtilise) {
            $error = "Cette adresse e-mail est déjà utilisée par un autre compte.";
        } else {
            $stmt = $pdo->prepare('SELECT id_essai FROM essai WHERE id_client = ? AND id_voiture = ? AND date_essai = ? AND heure_essai = ?');
            $stmt->execute([$id_client, $id_voiture_post, $date, $heure]);
            if ($stmt->fetchColumn()) {
                $error = 'Vous avez déjà envoyé une demande pour ce véhicule à cette date et à cette heure.';
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

                $_SESSION['essai_envoye'] = true;
                header('Location: demande_essai.php', true, 303);
                exit();
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $error = "Erreur lors de l'envoi de la demande.";
            }
            }
        }
    }
}
include 'includes/header.php';
?>

<div class="container section">
    <div class="form-card wide">
        <h1 class="form-title">Demande d’essais</h1>
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

                <label for="id_voiture">Voiture</label><select id="id_voiture" name="id_voiture" required>
                    <option value="">Choisir une voiture</option>
                    <?php foreach ($voitures as $voiture) { ?>
                        <option value="<?php echo $voiture['id_voiture']; ?>"
                            <?php echo $id_voiture_prenue == $voiture['id_voiture'] ? 'selected' : ''; ?>>
                            <?php echo e($voiture['nom_marque'] . ' ' . $voiture['modele']); ?>
                        </option>
                    <?php } ?>
                </select>

                <div class="form-row">
                    <div><label for="date">Date de l’essai</label><input id="date" type="date" name="date" min="<?php echo $date_min; ?>" value="<?php echo e($date); ?>" required></div>
                    <div><label for="heure">Heure de l’essai</label><select id="heure" name="heure" required>
                        <option value="">Choisir une heure</option>
                        <?php foreach ($heures_disponibles as $heure) { ?>
                            <option value="<?php echo $heure; ?>" <?php echo $heureChoisie === $heure ? 'selected' : ''; ?>><?php echo $heure; ?></option>
                        <?php } ?>
                    </select></div>
                </div>

                <label for="nom">Nom complet</label><input id="nom" type="text" name="nom" maxlength="100" autocomplete="name" value="<?php echo e($client_infos['nom'] ?? ''); ?>" required>
                <label for="email">Adresse e-mail</label><input id="email" type="email" name="email" maxlength="150" autocomplete="email" value="<?php echo e($client_infos['email'] ?? ''); ?>" required>
                <label for="telephone">Téléphone</label><input id="telephone" type="tel" name="telephone" maxlength="20" autocomplete="tel" value="<?php echo e($client_infos['telephone'] ?? ''); ?>" required>
                <label for="adresse">Adresse</label><input id="adresse" type="text" name="adresse" maxlength="255" autocomplete="street-address" value="<?php echo e($client_infos['adresse'] ?? ''); ?>" required>

                <button type="submit" class="btn btn-main btn-block">Envoyer la demande</button>
            </form>
        <?php } ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
