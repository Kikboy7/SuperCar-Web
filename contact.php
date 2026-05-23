<?php
session_start();
include 'includes/db.php';

$client = null;

if (isset($_SESSION['client'])) {
    $stmt = $pdo->prepare("SELECT nom, email FROM client WHERE id_client = ?");
    $stmt->execute([$_SESSION['client']]);
    $client = $stmt->fetch();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $contenu = trim($_POST['message']);
    $id_client = $client ? $_SESSION['client'] : null;

    if (!empty($nom) && !empty($email) && !empty($contenu)) {

        $stmt = $pdo->prepare("
            INSERT INTO message (nom, email, message, date_message, id_client, statut_message)
            VALUES (?, ?, ?, NOW(), ?, 'nouveau')
        ");

        $stmt->execute([$nom, $email, $contenu, $id_client]);

        $success = "Votre message a bien été envoyé. Notre équipe vous répondra rapidement.";
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Contact | SuperCar</title>
<style>
* {
            box-sizing: border-box;
        }

         body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: white;
            background:
                linear-gradient(rgba(5, 7, 12, 0.45), rgba(5, 7, 12, 0.65)),
                url("images/background4.png") center/cover no-repeat fixed;
            min-height: 100vh;
        }

        
         .navbar {
            width: 100%;
            background: rgba(0, 0, 0, 0.82);
            border-bottom: 1px solid rgba(211, 168, 90, 0.22);
            padding: 14px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(6px);
        }

         .navbar .logo img {
            height: 50px;
        }

         .navbar nav {
            display: flex;
            align-items: center;
            gap: 26px;
        }

         .navbar nav a {
            color: rgba(255,255,255,0.82);
            text-decoration: none;
            font-size: 15px;
            transition: 0.3s;
        }

         .navbar nav a:hover,
.navbar nav a.active {
            color: #f5d08d;
        }

         .user-name {
            color: #f5d08d;
            font-size: 15px;
        }

        
         .page-wrapper {
            width: 100%;
            min-height: 100vh;
            padding: 50px 20px 90px;
        }

         .hero {
            max-width: 1200px;
            margin: 40px auto 60px;
            padding: 20px;
            animation: fadeUp 0.8s ease;
        }

         .hero small {
            display: inline-block;
            color: #d3a85a;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 15px;
            font-size: 13px;
        }

         .hero h1 {
            font-size: 58px;
            margin: 0 0 20px;
            line-height: 1.1;
            font-weight: 700;
            max-width: 760px;
        }

         .hero p {
            max-width: 760px;
            color: rgba(255,255,255,0.78);
            font-size: 17px;
            line-height: 1.8;
            margin: 0;
        }

         .contact-layout {
            max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
            align-items: stretch;
        }

         .glass-card {
            background: rgba(10, 12, 18, 0.84);
            border: 1px solid rgba(211,168,90,0.18);
            border-radius: 24px;
            box-shadow: 0 20px 55px rgba(0,0,0,0.45);
            overflow: hidden;
            animation: fadeUp 0.9s ease;
            position: relative;
        }

         .glass-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 28px;
            right: 28px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(245,208,141,0.65), transparent);
        }

         .contact-card {
            padding: 34px;
            min-height: 575px;
        }

         .section-label {
            color: #d3a85a;
            text-transform: uppercase;
            letter-spacing: 1.6px;
            font-size: 12px;
            margin-bottom: 12px;
            display: inline-block;
        }

         .contact-card h2 {
            margin: 0 0 20px;
            font-size: 28px;
            font-weight: bold;
        }

         .card-text {
            color: rgba(255,255,255,0.70);
            line-height: 1.7;
            margin-bottom: 24px;
            font-size: 15px;
        }

        
         .info-list {
            display: flex;
            flex-direction: column;
            gap: 17px;
        }

         .info-line {
            display: flex;
            gap: 15px;
            padding: 18px;
            border-radius: 17px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
            transition: 0.35s ease;
        }

         .info-line:hover {
            transform: translateX(7px);
            border-color: rgba(211,168,90,0.38);
            background: rgba(255,255,255,0.065);
        }

         .info-line span {
            font-size: 26px;
            width: 32px;
            text-align: center;
        }

         .info-line h3 {
            margin: 0 0 6px;
            color: #f5d08d;
            font-size: 17px;
        }

         .info-line p {
            margin: 0;
            color: rgba(255,255,255,0.70);
            line-height: 1.5;
            font-size: 14px;
        }

        
         .map-frame {
            width: 100%;
            height: 315px;
            border: 0;
            border-radius: 18px;
            margin-bottom: 22px;
            display: block;
            filter: grayscale(35%) contrast(95%);
        }

         .map-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        
         form {
            margin-top: 10px;
        }

         input,
textarea {
            width: 100%;
            padding: 15px 16px;
            margin-bottom: 16px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.045);
            color: white;
            font-size: 15px;
            outline: none;
            transition: 0.3s ease;
            font-family: Arial, sans-serif;
        }

         input::placeholder,
textarea::placeholder {
            color: rgba(255,255,255,0.45);
        }

         input:focus,
textarea:focus {
            border-color: rgba(211,168,90,0.58);
            box-shadow: 0 0 0 3px rgba(211,168,90,0.12);
            background: rgba(255,255,255,0.065);
        }

         textarea {
            min-height: 170px;
            resize: vertical;
        }

         .btn-premium {
            display: inline-block;
            padding: 14px 24px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s ease;
            border: 1px solid rgba(211,168,90,0.45);
            cursor: pointer;
            text-align: center;
            font-size: 15px;
        }

         .btn-gold {
            background: linear-gradient(135deg, #c89a49, #f0cd8d);
            color: #111;
            border: none;
        }

         .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(200,154,73,0.28);
        }

         .btn-outline {
            background: transparent;
            color: #f5d08d;
        }

         .btn-outline:hover {
            background: rgba(211,168,90,0.12);
        }

         .success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.35);
            color: #86efac;
            padding: 14px;
            border-radius: 14px;
            margin-bottom: 18px;
            line-height: 1.5;
        }

         .error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
            padding: 14px;
            border-radius: 14px;
            margin-bottom: 18px;
            line-height: 1.5;
        }

         .form-note {
            margin-top: 18px;
            font-size: 13px;
            color: rgba(255,255,255,0.50);
            line-height: 1.6;
        }

         .bottom-note {
            max-width: 1200px;
            margin: 38px auto 0;
            padding: 28px 34px;
            border-radius: 22px;
            background: rgba(10, 12, 18, 0.72);
            border: 1px solid rgba(211,168,90,0.14);
            color: rgba(255,255,255,0.72);
            line-height: 1.8;
            animation: fadeUp 1s ease;
        }

         .bottom-note strong {
            color: #f5d08d;
        }

         @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(22px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

         @media (max-width: 1100px) {
.contact-layout {
                grid-template-columns: 1fr;
            }

             .contact-card {
                min-height: auto;
            }

             .hero h1 {
                font-size: 44px;
            }
}

         @media (max-width: 700px) {
.navbar {
                padding: 14px 20px;
                flex-direction: column;
                gap: 15px;
            }

             .navbar nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 14px;
            }

             .hero h1 {
                font-size: 34px;
            }

             .hero p {
                font-size: 15px;
            }

             .contact-card {
                padding: 25px;
            }
}
</style>

</head>

<body>
    
<?php include 'includes/header.php'; ?>

    <div class="page-wrapper">

        <section class="hero">
            <small>SuperCar • Contact</small>
            <h1>Un service premium commence par une vraie écoute.</h1>
            <p>
                Notre équipe vous accompagne pour toute demande liée à nos véhicules,
                aux essais, aux services ou à votre visite au siège social. Contactez-nous
                simplement et nous vous répondrons dans les meilleurs délais.
            </p>
        </section>

        <section class="contact-layout">

            <div class="glass-card contact-card">
                <span class="section-label">Informations</span>
                <h2>Coordonnées SuperCar</h2>

                <div class="info-list">

                    <div class="info-line">
                        <span>📍</span>
                        <div>
                            <h3>Adresse</h3>
                            <p>SuperCar<br>Port-Louis, Île Maurice</p>
                        </div>
                    </div>

                    <div class="info-line">
                        <span>📞</span>
                        <div>
                            <h3>Téléphone</h3>
                            <p>+230 212 0000<br>+230 5250 0000</p>
                        </div>
                    </div>

                    <div class="info-line">
                        <span>📧</span>
                        <div>
                            <h3>Email</h3>
                            <p>contact@supercar.mu<br>support@supercar.mu</p>
                        </div>
                    </div>

                    <div class="info-line">
                        <span>🕒</span>
                        <div>
                            <h3>Horaires</h3>
                            <p>Lun - Ven : 09h00 - 17h00<br>Sam : 09h00 - 13h00</p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="glass-card contact-card">
                <span class="section-label">Localisation</span>
                <h2>Nous trouver</h2>

                <p class="card-text">
                    Notre siège social se situe dans le centre afin d’accueillir les clients
                    souhaitant découvrir les véhicules, rencontrer un conseiller ou préparer une demande d’essai.
                </p>

                <iframe
                    class="map-frame"
                    src="https://maps.google.com/maps?q=Port%20Louis%20Mauritius&t=&z=13&ie=UTF8&iwloc=&output=embed">
                </iframe>

                <div class="map-actions">
                    <a class="btn-premium btn-gold"
                       href="https://www.google.com/maps/search/?api=1&query=Port+Louis+Mauritius"
                       target="_blank">
                        Ouvrir dans Google Maps
                    </a>

                    <a class="btn-premium btn-outline"
                       href="tel:+2302120000">
                        Appeler maintenant
                    </a>
                </div>
            </div>

            <div class="glass-card contact-card">
                <span class="section-label">Message</span>
                <h2>Envoyer une demande</h2>

                <p class="card-text">
                    Une question sur un véhicule, une demande d’information ou un besoin d’accompagnement ?
                    Envoyez-nous un message directement depuis le site.
                </p>

                <?php if ($success) { ?>
                    <div class="success"><?php echo $success; ?></div>
                <?php } ?>

                <?php if ($error) { ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php } ?>

                <form method="POST">
                    <input type="text" name="nom" placeholder="Votre nom complet"
                           value="<?php echo htmlspecialchars($client['nom'] ?? ''); ?>" required>

                    <input type="email" name="email" placeholder="Votre adresse email"
                           value="<?php echo htmlspecialchars($client['email'] ?? ''); ?>" required>

                    <textarea name="message" placeholder="Décrivez votre demande..." required></textarea>

                    <button type="submit" class="btn-premium btn-gold" style="width:100%;">
                        Envoyer le message
                    </button>
                </form>

                <p class="form-note">
                    Votre demande sera enregistrée afin que l’équipe SuperCar puisse la consulter
                    depuis l’espace d’administration du site.
                </p>
            </div>

        </section>

        <div class="bottom-note">
            <strong>SuperCar</strong> souhaite faciliter la communication avec ses clients.
            Cette page permet de centraliser les informations importantes, d’accéder rapidement
            à la localisation de l’entreprise et d’envoyer une demande directement depuis le site.
        </div>

    </div>

<?php include 'includes/footer.php'; ?>

</body>

</html>
