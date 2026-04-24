<?php
include 'includes/header.php';

if (!isset($_GET['id'])) {
    die("Voiture non trouvée");
}

if (!isset($_SESSION['client'])) {
    header("Location: login.php");
    exit();
}

$id_voiture = $_GET['id'];
$message = "";

// 🔥 SI FORMULAIRE ENVOYÉ
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $date = $_POST['date'];

    $id_client = $_SESSION['client'];

    $stmt = $pdo->prepare("INSERT INTO essai (date_essai, statut, id_client, id_voiture) VALUES (?, 'en attente', ?, ?)");
    $stmt->execute([$date, $id_client, $id_voiture]);

    $message = "✅ Demande envoyée avec succès !";
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
            margin-bottom: 30px;
        }

        input {
            width: 100%;
            padding: 14px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input:focus {
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
    </style>
</head>

<body>

    <div class="form-container">

        <h2>Demande d'essai</h2>

        <?php if ($message) { ?>
            <p class="success"><?php echo $message; ?></p>
        <?php } ?>

        <form method="POST">

            <input type="text" name="nom" placeholder="Nom complet" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="telephone" placeholder="Téléphone">
            <input type="text" name="adresse" placeholder="Adresse" required>
            <input type="date" name="date" required>

            <button type="submit">Envoyer la demande</button>

        </form>

    </div>

</body>

</html>