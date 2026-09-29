<?php
include 'includes/auth.php';

$pageTitle = "Page d'accueil";
$message = "";

// Enregistrer les textes saisis par l'administrateur.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!csrf_verify()) {
        $message = '<div class="alert alert--danger"><div class="alert__content"><p class="alert__text">Session de sécurité invalide.</p></div></div>';
    } else {
        $heroSurtitre = trim($_POST['hero_surtitre']);
        $heroTitreLigne1 = trim($_POST['hero_titre_ligne1']);
        $heroTitreLigne2 = trim($_POST['hero_titre_ligne2']);
        $heroDescription = trim($_POST['hero_description']);
        $ctaTitre = trim($_POST['cta_titre']);
        $ctaDescription = trim($_POST['cta_description']);

        if ($heroSurtitre == '' || $heroTitreLigne1 == '' || $heroTitreLigne2 == '' || $heroDescription == '' || $ctaTitre == '' || $ctaDescription == '') {
            $message = '<div class="alert alert--danger"><div class="alert__content"><p class="alert__text">Tous les champs sont obligatoires.</p></div></div>';
        } else {
            $stmt = $pdo->prepare("
                UPDATE contenu_accueil
                SET hero_surtitre = ?, hero_titre_ligne1 = ?, hero_titre_ligne2 = ?,
                    hero_description = ?, cta_titre = ?, cta_description = ?
                WHERE id_contenu = 1
            ");
            $stmt->execute([
                $heroSurtitre,
                $heroTitreLigne1,
                $heroTitreLigne2,
                $heroDescription,
                $ctaTitre,
                $ctaDescription
            ]);

            $message = '<div class="alert alert--success"><div class="alert__content"><p class="alert__text">La page d’accueil a bien été mise à jour.</p></div></div>';
        }
    }
}

// Charger les textes actuels dans le formulaire.
$contenu = $pdo->query("SELECT * FROM contenu_accueil WHERE id_contenu = 1")->fetch();

include 'includes/header.php';
?>

<?php if ($message) echo $message; ?>

<div class="card">
    <div class="card__header">
        <div>
            <h2 class="card__title">Texte principal</h2>
            <p class="form-help">Ces textes apparaissent sur la grande image en haut de la page d’accueil.</p>
        </div>
    </div>

    <form method="POST" class="form-grid">
        <?php echo csrf_field(); ?>

        <div class="form-group form-group--full">
            <label class="form-label form-label--required" for="hero_surtitre">Surtitre</label>
            <input type="text" id="hero_surtitre" name="hero_surtitre" class="form-input" maxlength="150" value="<?php echo e($contenu['hero_surtitre']); ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label form-label--required" for="hero_titre_ligne1">Première ligne du titre</label>
            <input type="text" id="hero_titre_ligne1" name="hero_titre_ligne1" class="form-input" maxlength="150" value="<?php echo e($contenu['hero_titre_ligne1']); ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label form-label--required" for="hero_titre_ligne2">Deuxième ligne du titre</label>
            <input type="text" id="hero_titre_ligne2" name="hero_titre_ligne2" class="form-input" maxlength="150" value="<?php echo e($contenu['hero_titre_ligne2']); ?>" required>
        </div>

        <div class="form-group form-group--full">
            <label class="form-label form-label--required" for="hero_description">Présentation</label>
            <textarea id="hero_description" name="hero_description" class="form-textarea" required><?php echo e($contenu['hero_description']); ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label form-label--required" for="cta_titre">Titre de l’appel à l’action</label>
            <input type="text" id="cta_titre" name="cta_titre" class="form-input" maxlength="150" value="<?php echo e($contenu['cta_titre']); ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label form-label--required" for="cta_description">Texte de l’appel à l’action</label>
            <textarea id="cta_description" name="cta_description" class="form-textarea" required><?php echo e($contenu['cta_description']); ?></textarea>
        </div>

        <div class="form-actions form-group--full">
            <button type="submit" class="btn btn--primary">Enregistrer les modifications</button>
            <a href="../index.php" class="btn btn--secondary" target="_blank" rel="noopener">Voir la page d’accueil</a>
        </div>
    </form>
</div>

    </main>
</div>

</body>
</html>
