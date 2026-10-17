<?php
/**
 * voitures.php - Catalogue des voitures.
 * Recherche unifiée, budget, critères complémentaires et tri.
 * Les liens de marque de l’accueil restent compatibles.
 */
$pageTitle = "Catalogue";
include 'includes/header.php';

// Ne jamais interpréter un tableau reçu dans l’URL comme une valeur de filtre.
function catalogue_parametre($cle) {
    return saisie($cle, $_GET);
}

$marques = $pdo->query('SELECT id_marque, nom_marque FROM marque ORDER BY nom_marque')->fetchAll(PDO::FETCH_KEY_PAIR);
$carburants = $pdo->query("SELECT DISTINCT carburant FROM voiture WHERE carburant IS NOT NULL AND carburant <> '' ORDER BY carburant")->fetchAll(PDO::FETCH_COLUMN);
$boites = $pdo->query("SELECT DISTINCT boite FROM voiture WHERE boite IS NOT NULL AND boite <> '' ORDER BY boite")->fetchAll(PDO::FETCH_COLUMN);
$tris = [
    'nom' => ['label' => 'Marque et modèle', 'sql' => 'm.nom_marque, v.modele, v.id_voiture'],
    'prix_asc' => ['label' => 'Prix croissant', 'sql' => 'v.prix ASC, v.id_voiture'],
    'prix_desc' => ['label' => 'Prix décroissant', 'sql' => 'v.prix DESC, v.id_voiture'],
    'annee_desc' => ['label' => 'Année la plus récente', 'sql' => 'v.annee DESC, v.id_voiture'],
    'puissance_desc' => ['label' => 'Puissance décroissante', 'sql' => 'v.puissance DESC, v.id_voiture'],
];
$filtreMarque = (int) catalogue_parametre('marque');
if (!isset($marques[$filtreMarque])) $filtreMarque = 0;
$filtreRecherche = mb_substr(catalogue_parametre('recherche'), 0, 100);
$budgetSaisi = catalogue_parametre('budget');
$budgetInvalide = $budgetSaisi !== '' && !preg_match('/^\d{1,12}$/D', $budgetSaisi);
$filtreBudget = !$budgetInvalide && $budgetSaisi !== '' ? (int) $budgetSaisi : null;
$filtreCarburant = catalogue_parametre('carburant');
if (!in_array($filtreCarburant, $carburants, true)) $filtreCarburant = '';
$filtreBoite = catalogue_parametre('boite');
if (!in_array($filtreBoite, $boites, true)) $filtreBoite = '';
$tri = catalogue_parametre('tri');
if (!isset($tris[$tri])) $tri = 'nom';

// Page actuelle (minimum 1) et nombre de voitures par page.
$page = max(1, min(1000000, (int) catalogue_parametre('page')));
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
    // Chaque mot doit apparaître : « BMW M3 » fonctionne dans une seule recherche.
    // Les caractères % et _ sont du texte, pas des jokers SQL.
    foreach (preg_split('/\s+/u', $filtreRecherche, -1, PREG_SPLIT_NO_EMPTY) as $mot) {
        $conditions[] = "CONCAT_WS(' ', m.nom_marque, v.modele, v.description) LIKE ? ESCAPE '!'";
        $parametres[] = '%' . strtr($mot, ['!' => '!!', '%' => '!%', '_' => '!_']) . '%';
    }
}
if ($filtreBudget !== null) {
    $conditions[] = 'v.prix <= ?';
    $parametres[] = $filtreBudget;
}
foreach (['carburant' => $filtreCarburant, 'boite' => $filtreBoite] as $champ => $valeur) {
    if ($valeur !== '') {
        $conditions[] = 'v.' . $champ . ' = ?';
        $parametres[] = $valeur;
    }
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
$totalVoitures = (int) $stmt->fetchColumn();
$pages = max(1, (int) ceil($totalVoitures / $parPage));
$ordre = $tris[$tri]['sql'];

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
    ORDER BY $ordre
    LIMIT $parPage OFFSET $offset
");
$stmt->execute($parametres);
$voitures = $stmt->fetchAll();

// Une seule source pour les liens : pagination et retrait d’un critère.
$filtres = ['marque' => $filtreMarque ?: '', 'recherche' => $filtreRecherche,
    'budget' => $filtreBudget, 'carburant' => $filtreCarburant, 'boite' => $filtreBoite,
    'tri' => $tri === 'nom' ? '' : $tri];
$lienCatalogue = function (array $changements = [], $ancre = 'catalogue') use ($filtres) {
    $valeurs = array_filter(array_replace($filtres, $changements), function ($valeur) {
        return $valeur !== '' && $valeur !== null;
    });
    return 'voitures.php' . ($valeurs ? '?' . http_build_query($valeurs) : '') . '#' . $ancre;
};
$criteres = [];
if ($filtreRecherche !== '') $criteres['recherche'] = 'Recherche : ' . $filtreRecherche;
if ($filtreMarque) $criteres['marque'] = 'Marque : ' . $marques[$filtreMarque];
if ($filtreBudget !== null) $criteres['budget'] = 'Jusqu’à Rs ' . number_format($filtreBudget, 0, ',', ' ');
if ($filtreCarburant !== '') $criteres['carburant'] = 'Motorisation : ' . $filtreCarburant;
if ($filtreBoite !== '') $criteres['boite'] = 'Boîte : ' . $filtreBoite;
?>

<!-- Entete de page -->
<section class="page-hero">
    <div class="container">
        <h1>Notre catalogue</h1>
        <p>D&eacute;couvrez nos v&eacute;hicules premium et trouvez celui qui vous correspond.</p>
    </div>
</section>

<div class="container section catalogue-section" id="catalogue">

    <!-- Barre de filtres -->
    <form method="GET" action="voitures.php#catalogue" class="filters catalogue-filters" role="search" aria-label="Rechercher un véhicule">
        <div class="catalogue-filter-heading"><span class="catalogue-eyebrow">Votre prochain véhicule</span><h2>Trouvez celui qui vous correspond</h2><p>Une marque ou un modèle en tête ? Affinez ensuite selon votre budget.</p></div>
        <?php if ($filtreMarque) { ?><input type="hidden" name="marque" value="<?php echo $filtreMarque; ?>"><?php } ?>
        <div class="catalogue-main-filters">
            <div class="field catalogue-search">
                <label for="catalogue-recherche">Marque, modèle ou mot-clé</label>
                <input id="catalogue-recherche" type="search" name="recherche" maxlength="100" placeholder="Ex. : BMW M3, Porsche, SUV…" value="<?php echo e($filtreRecherche); ?>">
            </div>
            <div class="field">
                <label for="catalogue-budget">Budget maximum (Rs)</label>
                <input id="catalogue-budget" type="number" name="budget" min="0" max="999999999999" step="1" inputmode="numeric" placeholder="Sans limite" value="<?php echo $filtreBudget !== null ? $filtreBudget : ''; ?>" <?php if ($budgetInvalide) { ?>aria-invalid="true" aria-describedby="catalogue-budget-erreur"<?php } ?>>
            </div>
            <button type="submit" class="btn btn-main">Rechercher <span aria-hidden="true">→</span></button>
        </div>
        <?php if ($budgetInvalide) { ?><p id="catalogue-budget-erreur" class="catalogue-error" role="alert">Le budget doit être un nombre entier positif ou nul, de 12 chiffres maximum. Ce critère n’a pas été appliqué.</p><?php } ?>
        <div class="catalogue-refinements">
            <details class="catalogue-more" <?php echo $filtreCarburant !== '' || $filtreBoite !== '' ? 'open' : ''; ?>>
                <summary>Plus de critères<?php echo $filtreCarburant !== '' || $filtreBoite !== '' ? ' · actifs' : ''; ?></summary>
                <div class="catalogue-extra-fields">
                    <div class="field"><label for="catalogue-carburant">Motorisation</label><select id="catalogue-carburant" name="carburant"><option value="">Toutes les motorisations</option><?php foreach ($carburants as $carburant) { ?><option value="<?php echo e($carburant); ?>" <?php echo $filtreCarburant === $carburant ? 'selected' : ''; ?>><?php echo e($carburant); ?></option><?php } ?></select></div>
                    <div class="field"><label for="catalogue-boite">Boîte de vitesses</label><select id="catalogue-boite" name="boite"><option value="">Toutes les boîtes</option><?php foreach ($boites as $boite) { ?><option value="<?php echo e($boite); ?>" <?php echo $filtreBoite === $boite ? 'selected' : ''; ?>><?php echo e($boite); ?></option><?php } ?></select></div>
                </div>
                <p class="catalogue-filter-help">Choisissez vos critères, puis cliquez sur « Rechercher ».</p>
            </details>
            <div class="field catalogue-sort"><label for="catalogue-tri">Trier par</label><select id="catalogue-tri" name="tri"><?php foreach ($tris as $cle => $choix) { ?><option value="<?php echo e($cle); ?>" <?php echo $tri === $cle ? 'selected' : ''; ?>><?php echo e($choix['label']); ?></option><?php } ?></select></div>
        </div>
    </form>

    <div class="catalogue-results" id="resultats">
        <div class="catalogue-results-heading"><h2><?php echo $totalVoitures; ?> véhicule<?php echo $totalVoitures > 1 ? 's' : ''; ?><?php echo $criteres ? ' trouvé' . ($totalVoitures > 1 ? 's' : '') : ' au catalogue'; ?></h2><p><?php echo $totalVoitures ? 'Affichage ' . ($offset + 1) . '–' . min($offset + $parPage, $totalVoitures) . ' · ' : ''; ?>Tri : <?php echo e($tris[$tri]['label']); ?></p></div>
        <?php if ($criteres) { ?><div class="catalogue-active-filters" aria-label="Critères actifs"><?php foreach ($criteres as $cle => $texte) { ?><a class="catalogue-filter-chip" href="<?php echo e($lienCatalogue([$cle => ''])); ?>" aria-label="<?php echo e('Retirer le critère ' . $texte); ?>"><?php echo e($texte); ?> <span aria-hidden="true">×</span></a><?php } ?><a class="catalogue-reset" href="voitures.php#catalogue">Tout effacer</a></div><?php } ?>
    </div>

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
                            <a href="detail.php?id=<?php echo $car['id_voiture']; ?>" class="btn btn-outline btn-sm">Voir les détails</a>
                            <a href="demande_essai.php?id=<?php echo $car['id_voiture']; ?>" class="btn btn-main btn-sm">R&eacute;server</a>
                        </div>
                    </div>
                </article>
            <?php } ?>
        </div>

        <!-- Pagination -->
        <?php if ($pages > 1) { ?>
            <nav class="pagination" aria-label="Pages du catalogue">
                <?php if ($page > 1) { ?>
                    <a href="<?php echo e($lienCatalogue(['page' => $page - 1], 'resultats')); ?>">&laquo; Pr&eacute;c&eacute;dent</a>
                <?php } ?>

                <?php for ($i = 1; $i <= $pages; $i++) { ?>
                    <?php if ($i == $page) { ?>
                        <span class="current" aria-current="page"><?php echo $i; ?></span>
                    <?php } else { ?>
                        <a href="<?php echo e($lienCatalogue(['page' => $i], 'resultats')); ?>" aria-label="Page <?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php } ?>
                <?php } ?>

                <?php if ($page < $pages) { ?>
                    <a href="<?php echo e($lienCatalogue(['page' => $page + 1], 'resultats')); ?>">Suivant &raquo;</a>
                <?php } ?>
            </nav>
        <?php } ?>

    <?php } else { ?>
        <!-- Aucun resultat -->
        <div class="empty-state">
            <h3>Aucune voiture trouv&eacute;e</h3>
            <p>Essayez un autre mot-clé, augmentez votre budget ou retirez un critère ci-dessus.</p>
            <a href="voitures.php#catalogue" class="btn btn-outline mt-2">Voir tout le catalogue</a>
        </div>
    <?php } ?>

</div>

<script>
// Le tri actualise le formulaire ; sans JavaScript, « Rechercher » reste utilisable.
document.getElementById('catalogue-tri').addEventListener('change', function () {
    this.form.requestSubmit();
});
</script>

<?php include 'includes/footer.php'; ?>
