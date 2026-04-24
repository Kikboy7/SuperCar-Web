<?php 
session_start();
include 'includes/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM client WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {

        $_SESSION['client'] = $user['id_client'];

        header("Location: index.php");
        exit();

    } else {
        $message = "Email ou mot de passe incorrect";
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

<h2>Connexion</h2>

<p class="message"><?php echo $message; ?></p>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>

    <button>Se connecter</button>
</form>

<a href="register.php">Créer un compte</a>

</div>

</body>
</html>