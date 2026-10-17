<?php
/**
 * compte.php - Page "Mon compte" : espace client central.
 * Affiche le profil du client connecte et ses demandes d'essai,
 * et permet de modifier ses coordonnees.
 * Si le visiteur n'est pas connecte, on le renvoie vers la page de connexion.
 */
$pageTitle = "Mon compte";
require_once __DIR__ . '/includes/init.php';

// Il faut etre connecte pour acceder a son espace.
if (!isset($_SESSION['client'])) {
    header("Location: login.php");
    exit();
}

$id_client = $_SESSION['client'];

$message = $_SESSION['profil_message'] ?? '';
unset($_SESSION['profil_message']);
$messageErreur = false;

// Modification du profil : uniquement en POST (avec protection CSRF).
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!csrf_verify()) {
        $message = "Session de sécurité invalide, veuillez réessayer.";
        $messageErreur = true;
    } else {
        $nom = saisie('nom');
        $telephone = saisie('telephone');
        $adresse = saisie('adresse');

        if (!coordonnees_valides($nom, $telephone, $adresse)) {
            $message = "Vérifiez le nom (100 caractères maximum), le téléphone (20 caractères) et l’adresse (255 caractères).";
            $messageErreur = true;
        } else {
            // Mise a jour des coordonnees (email non modifiable ici).
            $stmt = $pdo->prepare("
                UPDATE client SET nom = ?, telephone = ?, adresse = ? WHERE id_client = ?
            ");
            $stmt->execute([$nom, $telephone, $adresse, $id_client]);
            $_SESSION['profil_message'] = 'Profil mis à jour avec succès.';
            header('Location: compte.php#tab-profil', true, 303);
            exit();
        }
    }
}

// Profil complet du client (recharge apres une eventuelle mise a jour).
$stmt = $pdo->prepare("SELECT * FROM client WHERE id_client = ?");
$stmt->execute([$id_client]);
$clientInfo = $stmt->fetch();
if (!$clientInfo) { unset($_SESSION['client']); header('Location: login.php'); exit(); }

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
include 'includes/header.php';
?>

<div class="container section">
    <div class="section-head">
        <span class="section-tag">Espace client</span>
        <h1 class="section-title">Mon compte</h1>
        <p class="section-sub">Bienvenue <?php echo e($clientInfo['nom']); ?>, g&eacute;rez votre profil et vos r&eacute;servations.</p>
    </div>

    <?php if ($message) { ?>
        <div class="alert <?php echo $messageErreur ? 'alert-error' : 'alert-success'; ?>" role="status"><?php echo e($message); ?></div>
    <?php } ?>

    <div class="account-grid">

        <!-- ===== Carte profil ===== -->
        <div class="profile-card">
            <div class="profile-avatar">
                <?php echo e(mb_strtoupper(mb_substr($clientInfo['nom'], 0, 1))); ?>
            </div>
            <h2><?php echo e($clientInfo['nom']); ?></h2>
            <p class="muted"><?php echo e($clientInfo['email']); ?></p>

            <div class="profile-meta">
                <div>T&eacute;l&eacute;phone
                    <strong><?php echo e($clientInfo['telephone'] ?: 'Non renseigné'); ?></strong>
                </div>
                <div>Adresse
                    <strong><?php echo e($clientInfo['adresse'] ?: 'Non renseignée'); ?></strong>
                </div>
                <div>Client depuis
                    <strong><?php echo date('d/m/Y', strtotime($clientInfo['date_creation'])); ?></strong>
                </div>
            </div>

            <form method="POST" action="logout.php" class="logout-form mt-2"><?php echo csrf_field(); ?><button type="submit" class="btn btn-outline btn-block">Déconnexion</button></form>
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
                            <p>Heure : <strong><?php echo $e['heure_essai'] ? substr($e['heure_essai'], 0, 5) : 'Non renseignée'; ?></strong></p>
                            <p>Demande envoy&eacute;e le <?php echo date('d/m/Y', strtotime($e['date_demande'])); ?></p>
                        </div>

                        <span class="badge badge-<?php echo str_replace(' ', '-', $e['statut']); ?>">
                            Statut : <?php echo e([
                                'en attente' => 'En attente',
                                'valide' => 'Validée',
                                'refuse' => 'Refusée'
                            ][$e['statut']] ?? $e['statut']); ?>
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

                        <label for="profil-nom">Nom complet</label><input id="profil-nom" type="text" name="nom" maxlength="100" autocomplete="name" value="<?php echo e($clientInfo['nom']); ?>" required>
                        <label for="profil-email">Adresse e-mail (non modifiable)</label><input id="profil-email" type="email" value="<?php echo e($clientInfo['email']); ?>" disabled>
                        <label for="profil-telephone">Téléphone</label><input id="profil-telephone" type="tel" name="telephone" maxlength="20" autocomplete="tel" value="<?php echo e($clientInfo['telephone']); ?>">
                        <label for="profil-adresse">Adresse</label><input id="profil-adresse" type="text" name="adresse" maxlength="255" autocomplete="street-address" value="<?php echo e($clientInfo['adresse']); ?>">

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

    document.querySelector('.account-tabs').setAttribute('role', 'tablist');
    document.querySelector('.account-tabs').setAttribute('aria-label', 'Espace client');
    function activer(index, focus) {
        tabs.forEach(function (tab, i) {
            tab.classList.toggle('is-active', i === index);
            tab.setAttribute('aria-selected', String(i === index));
            tab.tabIndex = i === index ? 0 : -1;
            panels[i].classList.toggle('is-active', i === index);
        });
        if (focus) tabs[index].focus();
    }
    tabs.forEach(function (tab, i) {
        tab.id = 'onglet-' + i;
        tab.setAttribute('role', 'tab');
        tab.setAttribute('aria-controls', panels[i].id);
        panels[i].setAttribute('role', 'tabpanel');
        panels[i].setAttribute('aria-labelledby', tab.id);
        tab.addEventListener('click', function () { activer(i); });
        tab.addEventListener('keydown', function (event) {
            if (['ArrowRight', 'ArrowLeft', 'Home', 'End'].indexOf(event.key) < 0) return;
            event.preventDefault();
            activer(event.key === 'Home' ? 0 : event.key === 'End' ? tabs.length - 1 : (i + (event.key === 'ArrowRight' ? 1 : -1) + tabs.length) % tabs.length, true);
        });
    });
    function depuisAncre() { activer(location.hash === '#tab-profil' ? 1 : 0); }
    depuisAncre();
    window.addEventListener('hashchange', depuisAncre);
})();
</script>

<?php include 'includes/footer.php'; ?>
