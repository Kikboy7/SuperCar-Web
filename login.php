<?php
// On demarre la session pour pouvoir connecter le client.
session_start();

// Connexion a la base de donnees.
include 'includes/db.php';

$message = "";

// Si le formulaire est envoye, on verifie les identifiants.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $identifiant = trim($_POST['identifiant']);
    $password = $_POST['password'];

    // On recupere le compte login et le client associe.
    $stmt = $pdo->prepare("
        SELECT l.*, c.nom
        FROM login l
        JOIN client c ON l.id_client = c.id_client
        WHERE l.identifiant = ?
    ");
    $stmt->execute([$identifiant]);
    $user = $stmt->fetch();

    // Le mot de passe est compare avec password_verify car il est hash en base.
    if ($user && password_verify($password, $user['mot_de_passe'])) {

        // On stocke l'id du client en session pour le reconnaitre sur les autres pages.
        $_SESSION['client'] = $user['id_client'];

        header("Location: index.php");
        exit();

    } else {
        $message = "Identifiant ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
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
    max-width: 390px;
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

<h2>Connexion</h2>

<p class="message"><?php echo htmlspecialchars($message); ?></p>

<form method="POST">
    <input type="text" name="identifiant" placeholder="Identifiant" required>
    <input type="password" name="password" placeholder="Mot de passe" required>

    <button>Se connecter</button>
</form>

<a href="register.php">Cr&eacute;er un compte</a>

</div>

</body>
</html>
