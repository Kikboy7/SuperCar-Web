<?php 
include 'includes/header.php'; 
include 'includes/db.php'; 
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>SuperCar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
            background: #0f0f0f;
            color: white;
            font-family: 'Segoe UI', sans-serif;
        }

        
         .navbar {
            background: rgba(0,0,0,0.9);
            backdrop-filter: blur(10px);
        }

         .nav-link {
            color: #ccc !important;
            transition: 0.3s;
        }

         .nav-link:hover {
            color: #f39c12 !important;
        }

        
         .hero {
            height: 90vh;
            background: url("images/background1.webp") center/cover no-repeat;
            position: relative;
        }

         .hero::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
        }

         .overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

         .overlay h1 {
            font-size: 55px;
            font-weight: bold;
            letter-spacing: 2px;
        }

         .overlay p {
            font-size: 20px;
            margin-bottom: 20px;
            color: #ccc;
        }

         .btn-main {
            background: #f39c12;
            border: none;
            padding: 10px 25px;
            border-radius: 30px;
            color: black;
            font-weight: bold;
            transition: 0.3s;
        }

         .btn-main:hover {
            background: white;
            color: black;
        }

        
         .section {
            padding: 80px 0;
        }

         .section-title {
            text-align: center;
            margin-bottom: 50px;
            font-size: 35px;
            font-weight: bold;
        }

        
         .car-card {
            background: #1c1c1c;
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s;
        }

         .car-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: 0.4s;
        }

         .car-card:hover img {
            transform: scale(1.1);
        }

         .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 15px 30px rgba(0,0,0,0.6);
        }

         .car-info {
            padding: 15px;
            text-align: center;
        }

         .price {
            color: #f39c12;
            margin-top: 5px;
        }

        
         .cta {
            background: linear-gradient(45deg, #000, #111);
            text-align: center;
            padding: 60px;
        }

         .cta h2 {
            margin-bottom: 20px;
        }

        
         footer {
            background: #000;
        }
</style>

</head>

<body>

<!-- HERO -->
<section class="hero">
    <div class="overlay">
        <h1>Découvrez votre prochaine voiture</h1>
        <p>Performance. Luxe. Innovation.</p>
        <a href="voitures.php" class="btn-main">Voir les voitures</a>
    </div>
</section>

<!-- VOITURES PREVIEW -->
<section class="section container">
    <h2 class="section-title">Nos modèles</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="car-card">
                <img src="images/bmw0.webp">
                <div class="car-info">
                    <h4>BMW M3</h4>
                    <p class="price">Rs 2,500,000</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="car-card">
                <img src="images/audi0.jpg">
                <div class="car-info">
                    <h4>Audi RS6</h4>
                    <p class="price">Rs 3,200,000</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="car-card">
                <img src="images/mercedes0.jpg">
                <div class="car-info">
                    <h4>Mercedes AMG</h4>
                    <p class="price">Rs 3,800,000</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta">
    <h2>Envie de tester une voiture ?</h2>
    <a href="presentation.php" class="btn-main">Réserver un essai</a>
</section>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>