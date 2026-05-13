<?php
include 'includes/header.php';

$isLogged = isset($_SESSION['client']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Réserver un essai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #111;
            color: white;
        }

        
         .hero {
            height: 100vh;
            background: url('images/background2.png') center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

         .hero::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
        }

         .hero-content {
            position: relative;
            z-index: 1;
        }

         .hero h1 {
            font-size: 50px;
            margin-bottom: 10px;
        }

         .hero p {
            font-size: 20px;
            margin-bottom: 30px;
        }

        
         .btn {
            padding: 15px 30px;
            background: white;
            color: black;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
        }

         .btn:hover {
            background: #ccc;
        }

        
         .section {
            padding: 80px 20px;
            text-align: center;
            background-image: url("images/background3.png");
            background-size: cover;
            background-position: center;
        }

         .section h2 {
            font-size: 35px;
            margin-bottom: 20px;
        }

         .section p {
            max-width: 700px;
            margin: auto;
            color: #ffffff;
        }

        

         .steps {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 40px;
        }

         .step {
            background: #4b4b4b;
            padding: 30px;
            border-radius: 10px;
            width: 220px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
        }

         .step:hover {
            transform: scale(1.08);
            background: #6b6262;
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.5);
        }

        
         .step a {
            text-decoration: none;
            color: white;
            display: block;
        }

         .step h3 {
            font-size: 28px;
            margin-bottom: 10px;
        }
</style>

</head>

<body>

    <div class="hero">
        <div class="hero-content">
            <h1>Réservez votre essai</h1>
            <p>Vivez une expérience de conduite unique</p>
            <?php if ($isLogged) { ?>
                <a href="voitures.php" class="btn">Choisir une voiture</a>
            <?php } else { ?>
                <a href="login.php" class="btn">Se connecter</a>
            <?php } ?>
        </div>
    </div>

    <div class="section">
        <h2>Pourquoi réserver un essai ?</h2>
        <br><br>
        <p>
            Réserver un essai chez SuperCar, c'est bien plus qu'un simple test de véhicule.
            C'est une véritable immersion dans l'univers de la performance, du luxe et de l'innovation automobile.
            <br><br>
            Lors de votre essai, vous avez l'opportunité de découvrir nos véhicules dans des conditions réelles de
            conduite,
            afin de ressentir pleinement leur puissance, leur confort et leur précision.
            <br><br>
            Chaque détail compte : l'accélération, la tenue de route, le silence à bord ou encore les technologies
            embarquées.
            <br><br>
            Nos experts sont également à votre disposition pour vous accompagner tout au long de l'expérience.
            Ils vous conseillent, répondent à vos questions et vous aident à trouver le véhicule parfaitement adapté à
            vos besoins.
            <br><br>
            Que vous soyez passionné d'automobile ou à la recherche de votre future voiture,
            l'essai est une étape essentielle pour faire un choix éclairé en toute confiance.
            <br><br>
            Avec SuperCar, vous ne choisissez pas seulement une voiture...
            vous vivez une expérience.
        </p>

        <br><br><br><br>

        <h2>Comment ça marche ?</h2>

        <br>

        <div class="steps">

            <div class="step">
                <a href="<?php echo $isLogged ? 'voitures.php' : 'login.php'; ?>">
                    <h3>1</h3>
                    <p><?php echo $isLogged ? 'Accéder au catalogue' : 'Créer un compte'; ?></p>
                </a>
            </div>

            <div class="step">
                <a href="voitures.php">
                    <h3>2</h3>
                    <p>Choisir une voiture</p>
                </a>
            </div>

            <div class="step">
                <a href="voitures.php">
                    <h3>3</h3>
                    <p>Réserver un essai</p>
                </a>
            </div>

        </div>

    </div>

<?php include 'includes/footer.php'; ?>

</body>

</html>
