<?php
/**
 * voitures.php - Catalogue des voitures.
 * Fonctionnalites :
 * - recherche par mot-cle (modele, marque, description) ;
 * - filtre par marque ;
 * - pagination dynamique (LIMIT / OFFSET) : 6 voitures par page.
 */
$pageTitle = "Catalogue";
include 'includes/header.php';

// Parametres recus depuis l'URL (formulaire de filtre / liens de pagination).
$filtreMarque   = isset($_GET['marque'])  ? (int) $_GET['marque'] : 0;
$filtreRecherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';

// Page actuelle (minimum 1) et nombre de voitures par page.
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$parPage = 6;
$offset = ($page - 1) * $parPage;

// Construction de la condition SQL en fonction des filtres choisis.
$conditions = [];
$parametres = [];

if ($filtreMarque > 0) {
    $conditions[] = "v.id_marque = ?";
    $parametres[] = $filtreMarque;
}

if ($filtreRecherche !== '') {
    // LIKE permet une recherche partielle (exemple : "rs" trouve RS6, RS3...).
    $conditions[] = "(v.modele LIKE ? OR m.nom_marque LIKE ? OR v.description LIKE ?)";
    $terme = '%' . $filtreRecherche . '%';
    $parametres[] = $terme;
    $parametres[] = $terme;
    $parametres[] = $terme;
}

$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Nombre total de resultats (sert a calculer le nombre de pages).
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM voiture v
    JOIN marque m ON v.id_marque = m.id_marque
    $where
");
$stmt->execute($parametres);
$totalVoitures = $stmt->fetchColumn();
$pages = max(1, ceil($totalVoitures / $parPage));

// On limite la page demandee au nombre de pages disponible.
if ($page > $pages) {
    $page = $pages;
    $offset = ($page - 1) * $parPage;
}

// Les voitures de la page courante.
// LIMIT et OFFSET sont des entiers calcules et verifies par (int) : on peut les
// integrer directement dans la requete (PDO ne sait pas les passer en parametre).
$stmt = $pdo->prepare("
    SELECT v.*, m.nom_marque
    FROM voiture v
    JOIN marque m ON v.id_marque = m.id_marque
    $where
    ORDER BY m.nom_marque, v.modele
    LIMIT $parPage OFFSET $offset
");
$stmt->execute($parametres);
$voitures = $stmt->fetchAll();

// Toutes les marques pour le menu deroulant du filtre.
$marques = $pdo->query("SELECT * FROM marque ORDER BY nom_marque")->fetchAll();

// On garde les filtres dans les liens de pagination.
function lien_pagination($page, $marque, $recherche) {
    return "voitures.php?page=$page&marque=$marque&recherche=" . urlencode($recherche);
}
?>

<!-- Entete de page -->
<section class="page-hero">
    <div class="container">
        <h1>Notre catalogue</h1>
        <p>D&eacute;couvrez nos v&eacute;hicules premium et trouvez celui qui vous correspond.</p>
    </div>
</section>

<div class="container section">

    <!-- Barre de filtres -->
    <form method="GET" action="voitures.php" class="filters">
        <div class="field">
            <label>Marque</label>
            <select name="marque">
                <option value="0">Toutes les marques</option>
                <?php foreach ($marques as $m) { ?>
                    <option value="<?php echo $m['id_marque']; ?>" <?php echo $filtreMarque == $m['id_marque'] ? 'selected' : ''; ?>>
                        <?php echo e($m['nom_marque']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="field">
            <label>Recherche</label>
            <input type="text" name="recherche" placeholder="M3, RS6, Mercedes..." value="<?php echo e($filtreRecherche); ?>">
        </div>

        <button type="submit" class="btn btn-main">Filtrer</button>
    </form>

    <?php if (count($voitures) > 0) { ?>

        <div class="car-grid">
            <?php foreach ($voitures as $car) { ?>
                <article class="car-card">
                    <a href="detail.php?id=<?php echo $car['id_voiture']; ?>" class="thumb">
                        <span class="brand-chip"><?php echo e($car['nom_marque']); ?></span>
                        <img src="images/<?php echo e($car['image']); ?>" alt="<?php echo e($car['nom_marque'] . ' ' . $car['modele']); ?>" loading="lazy">
                    </a>
                    <div class="body">
                        <h3><?php echo e($car['nom_marque'] . ' ' . $car['modele']); ?></h3>
                        <span class="price">Rs <?php echo number_format($car['prix'], 0, ',', ' '); ?></span>

                        <div class="specs">
                            <?php if ($car['puissance']) { ?><span class="spec-chip"><?php echo (int) $car['puissance']; ?> ch</span><?php } ?>
                            <?php if ($car['carburant']) { ?><span class="spec-chip"><?php echo e($car['carburant']); ?></span><?php } ?>
                            <?php if ($car['boite']) { ?><span class="spec-chip"><?php echo e($car['boite']); ?></span><?php } ?>
                            <?php if ($car['annee']) { ?><span class="spec-chip"><?php echo (int) $car['annee']; ?></span><?php } ?>
                        </div>

                        <div class="actions">
                            <a href="detail.php?id=<?php echo $car['id_voiture']; ?>" class="btn btn-outline btn-sm">Voir d&eacute;tails</a>
                            <a href="demande_essai.php?id=<?php echo $car['id_voiture']; ?>" class="btn btn-main btn-sm">R&eacute;server</a>
                        </div>
                    </div>
                </article>
            <?php } ?>
        </div>

        <!-- Pagination -->
        <?php if ($pages > 1) { ?>
            <nav class="pagination">
                <?php if ($page > 1) { ?>
                    <a href="<?php echo lien_pagination($page - 1, $filtreMarque, $filtreRecherche); ?>">&laquo; Pr&eacute;c&eacute;dent</a>
                <?php } ?>

                <?php for ($i = 1; $i <= $pages; $i++) { ?>
                    <?php if ($i == $page) { ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php } else { ?>
                        <a href="<?php echo lien_pagination($i, $filtreMarque, $filtreRecherche); ?>"><?php echo $i; ?></a>
                    <?php } ?>
                <?php } ?>

                <?php if ($page < $pages) { ?>
                    <a href="<?php echo lien_pagination($page + 1, $filtreMarque, $filtreRecherche); ?>">Suivant &raquo;</a>
                <?php } ?>
            </nav>
        <?php } ?>

    <?php } else { ?>
        <!-- Aucun resultat -->
        <div class="empty-state">
            <h3>Aucune voiture trouv&eacute;e</h3>
            <p>Essayez de modifier votre recherche ou votre filtre de marque.</p>
            <a href="voitures.php" class="btn btn-outline mt-2">R&eacute;initialiser</a>
        </div>
    <?php } ?>

</div>

<?php include 'includes/footer.php'; ?>