<?php
// On demarre la session pour pouvoir retenir que l'admin est connecte.
session_start();

// On utilise la meme connexion a la base que le reste du site.
include '../includes/db.php';

$message = "";

// Si le formulaire est envoye, on essaie de connecter l'administrateur.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $identifiant = trim($_POST['identifiant']);
    $password = $_POST['password'];

    // On cherche un administrateur qui possede cet identifiant.
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE identifiant = ?");
    $stmt->execute([$identifiant]);
    $admin = $stmt->fetch();

    // password_verify compare le mot de passe saisi avec le mot de passe hash en base.
    if ($admin && password_verify($password, $admin['mot_de_passe'])) {
        // Si la connexion est correcte, on garde l'id admin dans la session.
        $_SESSION['admin'] = $admin['id_admin'];

        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Identifiant ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Admin | Connexion</title>

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            background: #eef3f8;
            color: #1f2937;
        }

        .login-box {
            width: 360px;
            background: white;
            padding: 35px;
            border-radius: 8px;
            border: 1px solid #dbe3ee;
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.12);
        }

        h1 {
            text-align: center;
            margin: 0 0 8px;
            color: #1d4ed8;
            font-size: 30px;
        }

        .subtitle {
            text-align: center;
            margin: 0 0 25px;
            color: #64748b;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 13px;
            margin-bottom: 15px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            background: #f8fafc;
            color: #1f2937;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 5px;
            background: #2563eb;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            background: #1d4ed8;
        }

        .message {
            text-align: center;
            color: #dc2626;
            background: #fee2e2;
            border: 1px solid #fecaca;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .back-link {
            display: block;
            margin-top: 18px;
            text-align: center;
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h1>Admin</h1>
        <p class="subtitle">Connexion au back-office SuperCar</p>

        <?php if ($message) { ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>

        <form method="POST">
            <input type="text" name="identifiant" placeholder="Identifiant" required>
            <input type="password" name="password" placeholder="Mot de passe" required>

            <button type="submit">Se connecter</button>
        </form>

        <a href="../index.php" class="back-link">Retour au site</a>
    </div>

</body>

</html>
