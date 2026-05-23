<?php
include 'includes/header.php';

if (!isset($_SESSION['client'])) {
    header("Location: login.php");
    exit();
}

$id_client = $_SESSION['client'];
$message = "";
$error = "";
$id_voiture_selectionnee = $_GET['id'] ?? "";

$heures_disponibles = [
    '08:00',
    '09:00',
    '10:00',
    '11:00',
    '12:00',
    '13:00',
    '14:00',
    '15:00',
    '16:00',
    '17:00',
    '18:00'
];

// Liste des voitures pour le menu deroulant.
$stmt = $pdo->query("
    SELECT voiture.id_voiture, voiture.modele, marque.nom_marque
    FROM voiture
    JOIN marque ON voiture.id_marque = marque.id_marque
    ORDER BY marque.nom_marque, voiture.modele
");
$voitures = $stmt->fetchAll();

// Informations du client pour pre-remplir le formulaire.
$stmt = $pdo->prepare("SELECT nom, email, telephone, adresse FROM client WHERE id_client = ?");
$stmt->execute([$id_client]);
$client_infos = $stmt->fetch();

if (!$client_infos) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_voiture_selectionnee = $_POST['id_voiture'];
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $adresse = trim($_POST['adresse']);
    $date = $_POST['date'];
    $heure = $_POST['heure'];

    if (empty($id_voiture_selectionnee) || empty($nom) || empty($email) || empty($adresse) || empty($date) || empty($heure)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } elseif (!ctype_digit($id_voiture_selectionnee)) {
        $error = "Veuillez choisir une voiture valide.";
    } elseif (!in_array($heure, $heures_disponibles)) {
        $error = "Veuillez choisir une heure entre 08:00 et 18:00.";
    } else {
        $stmt = $pdo->prepare("SELECT id_voiture FROM voiture WHERE id_voiture = ?");
        $stmt->execute([$id_voiture_selectionnee]);
        $voitureExiste = $stmt->fetch();

        if (!$voitureExiste) {
            $error = "La voiture selectionnee n'existe pas.";
        } else {
            try {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare("
                    UPDATE client
                    SET nom = ?, email = ?, telephone = ?, adresse = ?
                    WHERE id_client = ?
                ");
                $stmt->execute([$nom, $email, $telephone, $adresse, $id_client]);

                $stmt = $pdo->prepare("
                    INSERT INTO essai (date_essai, heure_essai, statut, id_client, id_voiture)
                    VALUES (?, ?, 'en attente', ?, ?)
                ");
                $stmt->execute([$date, $heure, $id_client, $id_voiture_selectionnee]);

                $pdo->commit();

                $message = "Votre demande d'essai a bien ete envoyee.";
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
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Demander un essai | SuperCar</title>
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #111;
    color: white;
}

.page {
    max-width: 780px;
    margin: 60px auto;
    padding: 20px;
}

.form-card {
    background: #1c1c1c;
    border-radius: 10px;
    padding: 35px;
    border: 1px solid rgba(255,255,255,0.08);
}

h1 {
    text-align: center;
    margin: 0 0 12px;
}

.intro {
    text-align: center;
    color: #bbb;
    margin-bottom: 28px;
}

input,
select {
    width: 100%;
    padding: 13px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #333;
    box-sizing: border-box;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

button,
.btn-light {
    display: inline-block;
    width: 100%;
    padding: 14px;
    border-radius: 999px;
    border: none;
    background: #f39c12;
    color: #111;
    font-weight: bold;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
}

.btn-light {
    margin-top: 12px;
    background: transparent;
    color: white;
    border: 1px solid #555;
}

.success {
    background: #d1fae5;
    color: #065f46;
    padding: 12px;
    border-radius: 5px;
    margin-bottom: 18px;
}

.error {
    background: #fee2e2;
    color: #991b1b;
    padding: 12px;
    border-radius: 5px;
    margin-bottom: 18px;
}

@media (max-width: 700px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<main class="page">
    <div class="form-card">
        <h1>Demander un essai</h1>
        <p class="intro">Choisissez une voiture, une date et un horaire disponible.</p>

        <?php if ($message) { ?>
            <p class="success"><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>

        <?php if ($error) { ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <form method="POST">
            <select name="id_voiture" required>
                <option value="">Choisir une voiture</option>
                <?php foreach ($voitures as $voiture) { ?>
                    <option value="<?php echo $voiture['id_voiture']; ?>"
                        <?php if ($id_voiture_selectionnee == $voiture['id_voiture']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($voiture['nom_marque'] . " " . $voiture['modele']); ?>
                    </option>
                <?php } ?>
            </select>

            <div class="form-row">
                <input type="date" name="date" required>

                <select name="heure" required>
                    <option value="">Choisir une heure</option>
                    <?php foreach ($heures_disponibles as $heure) { ?>
                        <option value="<?php echo $heure; ?>"><?php echo $heure; ?></option>
                    <?php } ?>
                </select>
            </div>

            <input type="text" name="nom" placeholder="Nom complet" value="<?php echo htmlspecialchars($client_infos['nom'] ?? ''); ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($client_infos['email'] ?? ''); ?>" required>
            <input type="text" name="telephone" placeholder="Telephone" value="<?php echo htmlspecialchars($client_infos['telephone'] ?? ''); ?>">
            <input type="text" name="adresse" placeholder="Adresse" value="<?php echo htmlspecialchars($client_infos['adresse'] ?? ''); ?>" required>

            <button type="submit">Envoyer la demande</button>
            <a href="reservation.php" class="btn-light">Voir mes reservations</a>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>
