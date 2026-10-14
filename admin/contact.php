<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/contact_config.php';
$pageTitle = 'Page contact';
$groupes = contact_champs();
$valeurs = contact_charger($pdo);
$erreurs = [];
$succes = !empty($_SESSION['contact_modifie']);
unset($_SESSION['contact_modifie']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!is_string($_POST['csrf'] ?? null) || !csrf_verify()) {
        $erreurs[] = 'Votre session a expiré. Veuillez réessayer.';
    } else {
        foreach ($groupes as $groupe) {
            foreach ($groupe['champs'] as $cle => $champ) {
                $valeur = is_string($_POST[$cle] ?? null) ? trim($_POST[$cle]) : '';
                $valeurs[$cle] = $valeur;
                if (($champ['obligatoire'] && $valeur === '') || !mb_check_encoding($valeur, 'UTF-8') || mb_strlen($valeur) > $champ['max']) {
                    $erreurs[] = $champ['label'] . ' : renseignez un texte valide de ' . $champ['max'] . ' caractères maximum.';
                } elseif ($champ['type'] === 'email' && !filter_var($valeur, FILTER_VALIDATE_EMAIL)) {
                    $erreurs[] = 'L’adresse e-mail n’est pas valide.';
                } elseif ($champ['type'] === 'tel' && $valeur !== '' && !preg_match('/^\+?[0-9][0-9 ()\.\-]{5,35}$/', $valeur)) {
                    $erreurs[] = $champ['label'] . ' : utilisez un numéro de téléphone valide.';
                } elseif ($cle === 'carte_zoom' && (!ctype_digit($valeur) || (int) $valeur < 3 || (int) $valeur > 20)) {
                    $erreurs[] = 'Le niveau de zoom doit être un entier compris entre 3 et 20.';
                }
            }
        }
        if (!$erreurs) {
            try {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare('INSERT INTO contenu_contact (cle, valeur) VALUES (?, ?) ON DUPLICATE KEY UPDATE valeur = ?');
                foreach ($valeurs as $cle => $valeur) {
                    $stmt->execute([$cle, $valeur, $valeur]);
                }
                $pdo->commit();
                $_SESSION['contact_modifie'] = true;
                header('Location: contact.php', true, 303);
                exit();
            } catch (PDOException $exception) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $erreurs[] = 'Les modifications n’ont pas été enregistrées. Vérifiez que la migration_contact.sql a été importée.';
            }
        }
    }
}
include __DIR__ . '/includes/header.php';
?>

<div class="contact-editor-intro">
    <p>Gérez les textes, les coordonnées et la carte de la page contact. Les coordonnées de l’en-tête et du pied de page sont synchronisées automatiquement.</p>
    <a href="../contact.php" class="btn btn--secondary" target="_blank" rel="noopener">Voir la page contact ↗</a>
</div>
<?php if ($succes) { ?><div class="alert alert--success" role="status"><p class="alert__text">La page contact et les coordonnées du site ont bien été mises à jour.</p></div><?php } ?>
<?php if ($erreurs) { ?><div class="alert alert--danger" role="alert"><div><strong>Les modifications n’ont pas été enregistrées.</strong><ul><?php foreach ($erreurs as $erreur) { ?><li><?php echo e($erreur); ?></li><?php } ?></ul></div></div><?php } ?>

<nav class="contact-editor-nav" aria-label="Sections de la page contact">
    <?php foreach ($groupes as $id => $groupe) { ?><a href="#<?php echo e($id); ?>"><?php echo e($groupe['titre']); ?></a><?php } ?>
</nav>
<form method="POST" class="contact-editor">
    <?php echo csrf_field(); ?>
    <?php foreach ($groupes as $id => $groupe) { ?>
        <section class="card" id="<?php echo e($id); ?>" aria-labelledby="titre-<?php echo e($id); ?>">
            <div class="card__header"><div><h2 class="card__title" id="titre-<?php echo e($id); ?>"><?php echo e($groupe['titre']); ?></h2><p class="form-help"><?php echo e($groupe['aide']); ?></p></div></div>
            <div class="form-grid">
                <?php foreach ($groupe['champs'] as $cle => $champ) { ?>
                    <div class="form-group<?php echo $champ['type'] === 'textarea' ? ' form-group--full' : ''; ?>">
                        <label for="<?php echo e($cle); ?>" class="form-label<?php echo $champ['obligatoire'] ? ' form-label--required' : ''; ?>"><?php echo e($champ['label']); ?></label>
                        <?php if ($champ['type'] === 'textarea') { ?>
                            <textarea id="<?php echo e($cle); ?>" name="<?php echo e($cle); ?>" class="form-textarea" rows="3" maxlength="<?php echo $champ['max']; ?>"<?php if ($champ['obligatoire']) { ?> required<?php } ?>><?php echo e($valeurs[$cle]); ?></textarea>
                        <?php } else { ?>
                            <input id="<?php echo e($cle); ?>" name="<?php echo e($cle); ?>" type="<?php echo e($champ['type']); ?>" class="form-input" maxlength="<?php echo $champ['max']; ?>" value="<?php echo e($valeurs[$cle]); ?>"<?php if ($champ['obligatoire']) { ?> required<?php } ?><?php if ($cle === 'carte_zoom') { ?> min="3" max="20" step="1"<?php } ?>>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
    <div class="contact-editor-actions"><span>Les changements seront visibles dès l’enregistrement.</span><button type="submit" class="btn btn--primary">Enregistrer les modifications</button></div>
</form>
</main>
</div>
</body>
</html>
