<?php
// On demarre la session et on se connecte a la base.
session_start();
include 'includes/db.php';

$message = "";

// Si le formulaire est envoye, on cree un nouveau compte client.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $identifiant = trim($_POST['identifiant']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (empty($nom) || empty($email) || empty($identifiant) || empty($password)) {
        $message = "Veuillez remplir tous les champs";
    } elseif ($password !== $confirm) {
        $message = "Les mots de passe ne correspondent pas";
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
            $message = "Identifiant ou email deja utilise";
        } else {
            try {
                // Le mot de passe est hash avant d'etre enregistre.
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

                $message = "Compte cree ! Vous pouvez vous connecter.";
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                $message = "Erreur lors de la creation du compte";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Inscription</title>
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background:
        linear-gradient(rgba(0, 0, 0, 0.72), rgba(0, 0, 0, 0.82)),
        url("images/background1.webp") center/cover no-repeat fixed;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    padding: 24px;
    box-sizing: border-box;
}


 .form-box {
    width: 100%;
    max-width: 420px;
    background: rgba(15, 15, 15, 0.92);
    padding: 42px 38px;
    border-radius: 8px;
    border: 1px solid rgba(243, 156, 18, 0.25);
    box-shadow: 0 25px 70px rgba(0,0,0,0.65);
    backdrop-filter: blur(12px);
    position: relative;
    overflow: hidden;
}

 .form-box::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: #f39c12;
}


 .form-box h2 {
    text-align: center;
    margin: 0 0 28px;
    color: #fff;
    font-size: 31px;
    font-weight: 800;
    letter-spacing: 0;
}


 .form-box input {
    width: 100%;
    padding: 15px 16px;
    margin-bottom: 15px;
    border: 1px solid rgba(255,255,255,0.11);
    border-radius: 6px;
    background: rgba(255,255,255,0.06);
    color: white;
    font-size: 15px;
    box-sizing: border-box;
    transition: 0.25s;
}

 .form-box input::placeholder {
    color: rgba(255,255,255,0.48);
}


 .form-box input:focus {
    outline: none;
    border-color: #f39c12;
    box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.16);
    background: rgba(255,255,255,0.09);
}


 .form-box button {
    width: 100%;
    padding: 15px;
    background: #f39c12;
    border: none;
    border-radius: 999px;
    color: #111;
    font-weight: 800;
    cursor: pointer;
    transition: 0.25s;
    text-transform: uppercase;
    letter-spacing: 0;
    margin-top: 5px;
}

 .form-box button:hover {
    background: white;
    transform: translateY(-2px);
    box-shadow: 0 12px 26px rgba(243, 156, 18, 0.24);
}


 .form-box a {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #f39c12;
    text-decoration: none;
    font-weight: 700;
    transition: 0.25s;
}

 .form-box a:hover {
    color: white;
}


 .message {
    text-align: center;
    margin: 0 0 15px;
    color: #f87171;
    min-height: 18px;
    line-height: 1.4;
}

 @media (max-width: 480px) {
.form-box {
        padding: 32px 24px;
    }

     .form-box h2 {
        font-size: 26px;
    }
}
</style>

</head>

<body>

<div class="form-box">

<h2>Créer un compte</h2>

<p class="message"><?php echo htmlspecialchars($message); ?></p>

<form method="POST">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="identifiant" placeholder="Identifiant" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <input type="password" name="confirm" placeholder="Confirmer mot de passe" required>

    <button>S'inscrire</button>
</form>

<a href="login.php">D&eacute;j&agrave; un compte ?</a>

</div>

</body>
</html>
