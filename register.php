<?php
session_start();
include 'includes/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // 🔥 Vérification
    if ($password !== $confirm) {
        $message = "Les mots de passe ne correspondent pas";
    } else {

        // 🔒 Hash
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO client (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $password_hash]);

        $message = "Compte créé !";
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
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* CONTAINER */
.form-box {
    background: rgba(20, 30, 50, 0.9);
    padding: 40px;
    border-radius: 15px;
    width: 350px;
    backdrop-filter: blur(10px);
    box-shadow: 0 0 40px rgba(0,0,0,0.5);
}

/* TITRE */
.form-box h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #cbd5f5;
}

/* INPUT */
.form-box input {
    width: 100%;
    padding: 14px;
    margin-bottom: 15px;
    border: none;
    border-radius: 8px;
    background: #0f172a;
    color: white;
    font-size: 14px;
}

.form-box input::placeholder {
    color: #94a3b8;
}

/* FOCUS */
.form-box input:focus {
    outline: none;
    box-shadow: 0 0 0 2px #38bdf8;
}

/* BUTTON */
.form-box button {
    width: 100%;
    padding: 14px;
    background: #38bdf8;
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

.form-box button:hover {
    background: #0ea5e9;
}

/* LINK */
.form-box a {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #94a3b8;
    text-decoration: none;
}

.form-box a:hover {
    color: white;
}

/* MESSAGE */
.message {
    text-align: center;
    margin-bottom: 10px;
    color: #f87171;
}
</style>

</head>

<body>

<div class="form-box">

<h2>Créer un compte</h2>

<p class="message"><?php echo $message; ?></p>

<form method="POST">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <input type="password" name="confirm" placeholder="Confirmer mot de passe" required>

    <button>S'inscrire</button>
</form>

<a href="login.php">Déjà un compte ?</a>

</div>

</body>
</html>