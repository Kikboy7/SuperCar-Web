<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/db.php';

// On recupere les services pour les afficher dans la page.
$stmt = $pdo->query("SELECT * FROM services ORDER BY id_services");
$services = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Services | SuperCar</title>
<style>
* {
            box-sizing: border-box;
        }

         body {
            margin: 0;
            background: #0f0f0f;
            color: white;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

         .services-hero {
            min-height: 420px;
            background:
                linear-gradient(rgba(0, 0, 0, 0.68), rgba(0, 0, 0, 0.88)),
                url("images/background3.png") center/cover no-repeat;
            display: flex;
            align-items: center;
            padding: 70px 20px;
        }

         .hero-content {
            max-width: 1120px;
            width: 100%;
            margin: auto;
        }

         .hero-content span {
            color: #f39c12;
            text-transform: uppercase;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 2px;
        }

         .hero-content h1 {
            max-width: 760px;
            margin: 14px 0 18px;
            font-size: 54px;
            line-height: 1.1;
        }

         .hero-content p {
            max-width: 720px;
            margin: 0;
            color: #d0d0d0;
            font-size: 17px;
            line-height: 1.7;
        }

         .services-section {
            max-width: 1120px;
            margin: auto;
            padding: 70px 20px 30px;
        }

         .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

         .service-card {
            background: #1c1c1c;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            padding: 28px;
            min-height: 255px;
            display: flex;
            flex-direction: column;
            transition: 0.3s;
        }

         .service-card:hover {
            transform: translateY(-8px);
            border-color: rgba(243,156,18,0.55);
            box-shadow: 0 18px 35px rgba(0,0,0,0.45);
        }

         .service-number {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #f39c12;
            color: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            margin-bottom: 22px;
        }

         .service-card h2 {
            margin: 0 0 14px;
            font-size: 24px;
        }

         .service-card p {
            margin: 0 0 24px;
            color: #bcbcbc;
            line-height: 1.7;
            flex: 1;
        }

         .service-price {
            color: #f39c12;
            font-weight: bold;
            font-size: 16px;
        }

         .empty-message {
            text-align: center;
            color: #bbb;
            padding: 50px;
            background: #1c1c1c;
            border-radius: 8px;
        }

         .services-cta {
            max-width: 1120px;
            margin: 45px auto 0;
            padding: 38px 20px 80px;
            text-align: center;
        }

         .services-cta h2 {
            margin: 0 0 18px;
            font-size: 34px;
        }

         .actions {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

         .btn-main,
.btn-outline {
            display: inline-block;
            padding: 13px 26px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

         .btn-main {
            background: #f39c12;
            color: #111;
        }

         .btn-main:hover {
            background: white;
        }

         .btn-outline {
            color: white;
            border: 1px solid rgba(255,255,255,0.25);
        }

         .btn-outline:hover {
            border-color: #f39c12;
            color: #f39c12;
        }

         @media (max-width: 900px) {
.services-grid {
                grid-template-columns: 1fr;
            }

             .hero-content h1 {
                font-size: 40px;
            }
}
</style>

</head>

<body>

<?php include 'includes/header.php'; ?>

<section class="services-hero">
    <div class="hero-content">
        <span>Services SuperCar</span>
        <h1>Un accompagnement simple avant, pendant et après l'achat.</h1>
        <p>
            SuperCar aide les clients à choisir leur véhicule, organiser un essai
            et préparer les prochaines étapes avec un conseiller.
        </p>
    </div>
</section>

<main class="services-section">
    <?php if (count($services) > 0) { ?>
        <div class="services-grid">
            <?php foreach ($services as $index => $service) { ?>
                <article class="service-card">
                    <div class="service-number"><?php echo $index + 1; ?></div>

                    <h2><?php echo htmlspecialchars($service['nom_services']); ?></h2>

                    <p><?php echo htmlspecialchars($service['description_services']); ?></p>

                    <div class="service-price">
                        <?php if ($service['prix_services'] > 0) { ?>
                            Rs <?php echo number_format($service['prix_services'], 0, ',', ' '); ?>
                        <?php } else { ?>
                            Service gratuit
                        <?php } ?>
                    </div>
                </article>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p class="empty-message">Aucun service disponible pour le moment.</p>
    <?php } ?>
</main>

<section class="services-cta">
    <h2>Envie de découvrir nos voitures ?</h2>
    <div class="actions">
        <a href="voitures.php" class="btn-main">Voir les voitures</a>
        <a href="contact.php" class="btn-outline">Nous contacter</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

</body>
</html>
