<?php
/**
 * compte.php - Page "Mon compte" : espace client central.
 * Affiche le profil du client connecte et ses demandes d'essai,
 * et permet de modifier ses coordonnees.
 * Si le visiteur n'est pas connecte, on le renvoie vers la page de connexion.
 */
$pageTitle = "Mon compte";
include 'includes/header.php';

// Il faut etre connecte pour acceder a son espace.
if (!isset($_SESSION['client'])) {
    header("Location: login.php");
    exit();
}

$id_client = $_SESSION['client'];

$message = "";

// Modification du profil : uniquement en POST (avec protection CSRF).
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!csrf_verify()) {
        $message = "Session de securite invalide, veuillez reessayer.";
    } else {
        $nom       = trim($_POST['nom'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $adresse   = trim($_POST['adresse'] ?? '');

        if ($nom === '') {
            $message = "Le nom ne peut pas etre vide.";
        } else {
            // Mise a jour des coordonnees (email non modifiable ici).
            $stmt = $pdo->prepare("
                UPDATE client SET nom = ?, telephone = ?, adresse = ? WHERE id_client = ?
            ");
            $stmt->execute([$nom, $telephone, $adresse, $id_client]);
            $message = "Profil mis &agrave; jour avec succ&egrave;s.";
        }
    }
}

// Profil complet du client (recharge apres une eventuelle mise a jour).
$stmt = $pdo->prepare("SELECT * FROM client WHERE id_client = ?");
$stmt->execute([$id_client]);
$clientInfo = $stmt->fetch();

// Demandes d'essai du client, avec la voiture et la marque associees.
$stmt = $pdo->prepare("
    SELECT essai.*, voiture.modele, voiture.image, marque.nom_marque
    FROM essai
    JOIN voiture ON essai.id_voiture = voiture.id_voiture
    JOIN marque ON voiture.id_marque = marque.id_marque
    WHERE essai.id_client = ?
    ORDER BY essai.date_essai DESC, essai.heure_essai DESC
");
$stmt->execute([$id_client]);
$essais = $stmt->fetchAll();
?>

<div class="container section">
    <div class="section-head">
        <span class="section-tag">Espace client</span>
        <h1 class="section-title">Mon compte</h1>
        <p class="section-sub">Bienvenue <?php echo e($clientInfo['nom']); ?>, g&eacute;rez votre profil et vos r&eacute;servations.</p>
    </div>

    <?php if ($message) { ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php } ?>

    <div class="account-grid">

        <!-- ===== Carte profil ===== -->
        <div class="profile-card">
            <div class="profile-avatar">
                <?php echo e(strtoupper(substr($clientInfo['nom'], 0, 1))); ?>
            </div>
            <h2><?php echo e($clientInfo['nom']); ?></h2>
            <p class="muted"><?php echo e($clientInfo['email']); ?></p>

            <div class="profile-meta">
                <div>T&eacute;l&eacute;phone
                    <strong><?php echo e($clientInfo['telephone'] ?: 'Non renseign&eacute;'); ?></strong>
                </div>
                <div>Adresse
                    <strong><?php echo e($clientInfo['adresse'] ?: 'Non renseign&eacute;e'); ?></strong>
                </div>
                <div>Client depuis
                    <strong><?php echo date('d/m/Y', strtotime($clientInfo['date_creation'])); ?></strong>
                </div>
            </div>

            <a href="logout.php" class="btn btn-outline btn-block mt-2">D&eacute;connexion</a>
        </div>

        <!-- ===== Contenu principal ===== -->
        <div>

            <!-- Onglets : Mes reservations / Modifier le profil -->
            <div class="account-tabs">
                <button type="button" class="is-active" data-panel="tab-reservations">Mes r&eacute;servations</button>
                <button type="button" data-panel="tab-profil">Modifier mon profil</button>
            </div>

            <!-- Onglet : reservations -->
            <div class="account-panel is-active" id="tab-reservations">
                <?php if (count($essais) == 0) { ?>
                    <div class="empty-state">
                        <h3>Aucune r&eacute;servation pour le moment</h3>
                        <p>Parcourez notre catalogue et r&eacute;servez votre premier essai.</p>
                        <a href="voitures.php" class="btn btn-main mt-2">Voir les voitures</a>
                    </div>
                <?php } ?>

                <?php foreach ($essais as $e) { ?>
                    <div class="reservation-card">
                        <?php if ($e['image']) { ?>
                            <img src="images/<?php echo e($e['image']); ?>" alt="<?php echo e($e['nom_marque'] . ' ' . $e['modele']); ?>">
                        <?php } ?>

                        <div class="reservation-info">
                            <h3><?php echo e($e['nom_marque'] . ' ' . $e['modele']); ?></h3>
                            <p>Date : <strong><?php echo date('d/m/Y', strtotime($e['date_essai'])); ?></strong></p>
                            <p>Heure : <strong><?php echo substr($e['heure_essai'], 0, 5); ?></strong></p>
                            <p>Demande envoy&eacute;e le <?php echo date('d/m/Y', strtotime($e['date_demande'])); ?></p>
                        </div>

                        <span class="badge badge-<?php echo str_replace(' ', '-', $e['statut']); ?>">
                            Statut : <?php echo e($e['statut']); ?>
                        </span>
                    </div>
                <?php } ?>
            </div>

            <!-- Onglet : modifier le profil -->
            <div class="account-panel" id="tab-profil">
                <div class="form-card">
                    <h2>Mes coordonn&eacute;es</h2>
                    <p class="auth-sub">Seul le nom, le t&eacute;l&eacute;phone et l'adresse sont modifiables.</p>

                    <form method="POST">
                        <?php echo csrf_field(); ?>

                        <input type="text" name="nom" placeholder="Nom complet" value="<?php echo e($clientInfo['nom']); ?>" required>
                        <input type="email" value="<?php echo e($clientInfo['email']); ?>" disabled title="L'adresse e-mail ne peut pas etre modifiee ici">
                        <input type="text" name="telephone" placeholder="T&eacute;l&eacute;phone" value="<?php echo e($clientInfo['telephone']); ?>">
                        <input type="text" name="adresse" placeholder="Adresse" value="<?php echo e($clientInfo['adresse']); ?>">

                        <button type="submit" class="btn btn-main btn-block">Enregistrer les modifications</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Onglets de la page compte : affiche le bon panneau au clic.
(function () {
    var tabs = document.querySelectorAll('.account-tabs button');
    var panels = document.querySelectorAll('.account-panel');

    for (var i = 0; i < tabs.length; i++) {
        tabs[i].addEventListener('click', function () {
            for (var j = 0; j < tabs.length; j++) {
                tabs[j].classList.toggle('is-active', tabs[j] === this);
            }
            for (var k = 0; k < panels.length; k++) {
                panels[k].classList.toggle('is-active', panels[k].id === this.getAttribute('data-panel'));
            }
        });
    }
})();
</script>

<?php include 'includes/footer.php'; ?>
