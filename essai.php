<?php
include 'includes/header.php';

// On doit obligatoirement avoir l'id d'une voiture dans l'URL.
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: voitures.php");
    exit();
}

// Un client doit etre connecte pour faire une demande d'essai.
if (!isset($_SESSION['client'])) {
    header("Location: login.php");
    exit();
}

// On recupere l'id de la voiture et l'id du client connecte.
$id_voiture = (int) $_GET['id'];
$id_client = $_SESSION['client'];
$message = "";
$error = "";
$heures_disponibles = [
    '09:00',
    '10:00',
    '11:00',
    '12:00',
    '13:00',
    '14:00',
    '15:00',
    '16:00'
];

// On verifie que la voiture existe bien dans la base.
$stmt = $pdo->prepare("
    SELECT voiture.*, marque.nom_marque
    FROM voiture
    JOIN marque ON voiture.id_marque = marque.id_marque
    WHERE voiture.id_voiture = ?
");
$stmt->execute([$id_voiture]);
$voiture = $stmt->fetch();

if (!$voiture) {
    header("Location: voitures.php");
    exit();
}

// On recupere les informations actuelles du client pour pre-remplir le formulaire.
$stmt = $pdo->prepare("SELECT nom, email, telephone, adresse FROM client WHERE id_client = ?");
$stmt->execute([$id_client]);
$client_infos = $stmt->fetch();

if (!$client_infos) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Si le formulaire est envoye, on met a jour le client puis on enregistre l'essai.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $adresse = trim($_POST['adresse']);
    $date = $_POST['date'];
    $heure = $_POST['heure'];

    if (empty($nom) || empty($email) || empty($adresse) || empty($date) || empty($heure)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } elseif (!in_array($heure, $heures_disponibles)) {
        $error = "Veuillez choisir une heure entre 08:00 et 18:00.";
    } else {
        try {
            // Transaction : l'update client et l'insert essai doivent fonctionner ensemble.
            $pdo->beginTransaction();

            // Mise a jour des informations personnelles du client.
            $stmt = $pdo->prepare("
                UPDATE client
                SET nom = ?, email = ?, telephone = ?, adresse = ?
                WHERE id_client = ?
            ");
            $stmt->execute([$nom, $email, $telephone, $adresse, $id_client]);

            // Creation de la demande d'essai.
            $stmt = $pdo->prepare("
                INSERT INTO essai (date_essai, heure_essai, statut, id_client, id_voiture)
                VALUES (?, ?, 'en attente', ?, ?)
            ");
            $stmt->execute([$date, $heure, $id_client, $id_voiture]);

            $pdo->commit();

            $message = "Demande envoyee avec succes !";

            $client_infos['nom'] = $nom;
            $client_infos['email'] = $email;
            $client_infos['telephone'] = $telephone;
            $client_infos['adresse'] = $adresse;
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $error = "Erreur lors de l'envoi de la demande.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Demande d'essai</title>
<style>
body {
            font-family: Arial;
            background: #f5f5f5;
            margin: 0;
        }

         .form-container {
            max-width: 500px;
            margin: 80px auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

         h2 {
            text-align: center;
            margin-bottom: 10px;
        }

         .car-name {
            text-align: center;
            color: #555;
            margin-bottom: 25px;
        }

         input,
         select {
            width: 100%;
            padding: 14px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
        }

         input:focus,
         select:focus {
            border-color: black;
            outline: none;
        }

         button {
            width: 100%;
            padding: 15px;
            background: black;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

         button:hover {
            background: #333;
        }

         .success {
            background: #d4edda;
            padding: 10px;
            border-radius: 5px;
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }

         .error {
            background: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            color: #842029;
            text-align: center;
            margin-bottom: 15px;
        }
</style>

</head>

<body>

    <div class="form-container">

        <h2>Demande d'essai</h2>
        <p class="car-name">
            <?php echo htmlspecialchars($voiture['nom_marque'] . " " . $voiture['modele']); ?>
        </p>

        <?php if ($message) { ?>
            <p class="success"><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>

        <?php if ($error) { ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <form method="POST">

            <input type="text" name="nom" placeholder="Nom complet" value="<?php echo htmlspecialchars($client_infos['nom'] ?? ''); ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($client_infos['email'] ?? ''); ?>" required>
            <input type="text" name="telephone" placeholder="Telephone" value="<?php echo htmlspecialchars($client_infos['telephone'] ?? ''); ?>">
            <input type="text" name="adresse" placeholder="Adresse" value="<?php echo htmlspecialchars($client_infos['adresse'] ?? ''); ?>" required>
            <input type="date" name="date" required>

            <select name="heure" required>
                <option value="">Choisir une heure</option>
                <?php foreach ($heures_disponibles as $heure_disponible) { ?>
                    <option value="<?php echo $heure_disponible; ?>">
                        <?php echo $heure_disponible; ?>
                    </option>
                <?php } ?>
            </select>

            <button type="submit">Envoyer la demande</button>

        </form>

    </div>

</body>

</html>
