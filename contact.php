<?php
/** Page contact : textes administrables, coordonnées partagées et messagerie. */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/contact_config.php';
$coordonneesSite = contact_charger($pdo);
$pageTitle = $coordonneesSite['page_titre'];
$contactClient = null;
if (isset($_SESSION['client'])) {
    $stmt = $pdo->prepare('SELECT id_client, nom, email FROM client WHERE id_client = ?');
    $stmt->execute([$_SESSION['client']]);
    $contactClient = $stmt->fetch();
}
$saisie = ['nom' => $contactClient['nom'] ?? '', 'email' => $contactClient['email'] ?? '', 'message' => ''];
$erreurs = [];
$succes = !empty($_SESSION['contact_envoye']);
unset($_SESSION['contact_envoye']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (array_keys($saisie) as $cle) {
        $saisie[$cle] = saisie($cle);
    }
    if (!is_string($_POST['csrf'] ?? null) || !csrf_verify()) {
        $erreurs['session'] = $coordonneesSite['erreur_session'];
    }
    if ($saisie['nom'] === '' || mb_strlen($saisie['nom']) > 100) {
        $erreurs['nom'] = $coordonneesSite['erreur_nom'];
    }
    if (mb_strlen($saisie['email']) > 150 || !filter_var($saisie['email'], FILTER_VALIDATE_EMAIL)) {
        $erreurs['email'] = $coordonneesSite['erreur_email'];
    }
    if ($saisie['message'] === '' || mb_strlen($saisie['message']) > 5000) {
        $erreurs['message'] = $coordonneesSite['erreur_message'];
    }
    if (!$erreurs) {
        $stmt = $pdo->prepare("INSERT INTO message (nom, email, message, date_message, id_client, statut_message) VALUES (?, ?, ?, NOW(), ?, 'nouveau')");
        $stmt->execute([$saisie['nom'], $saisie['email'], $saisie['message'], $contactClient['id_client'] ?? null]);
        $_SESSION['contact_envoye'] = true;
        // Une actualisation ne doit pas renvoyer le même message.
        header('Location: contact.php#nous-ecrire', true, 303);
        exit();
    }
}
include __DIR__ . '/includes/header.php';
?>

<div class="contact-page">
    <section class="contact-heading container" aria-labelledby="contact-title">
        <div>
            <span class="section-tag"><?php echo e($coordonneesSite['surtitre']); ?></span>
            <h1 id="contact-title"><?php echo e($coordonneesSite['titre']); ?></h1>
            <p><?php echo e($coordonneesSite['introduction']); ?></p>
        </div>
        <a class="contact-heading-location" href="#acces">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 6-8 11-8 11S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>
            <span><strong>SuperCar</strong><?php echo e($coordonneesSite['ville'] . ', ' . $coordonneesSite['pays']); ?></span>
            <span aria-hidden="true">↗</span>
        </a>
    </section>

    <div class="container contact-layout">
        <section class="contact-details contact-panel" aria-labelledby="coordonnees-title">
            <h2 id="coordonnees-title"><?php echo e($coordonneesSite['coordonnees_titre']); ?></h2>
            <p class="contact-panel-intro"><?php echo e($coordonneesSite['coordonnees_intro']); ?></p>
            <div class="contact-detail-row">
                <span class="contact-detail-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 6-8 11-8 11S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></span>
                <div><h3><?php echo e($coordonneesSite['adresse_label']); ?></h3><address><?php echo e($coordonneesSite['adresse']); ?><br><?php echo e($coordonneesSite['ville'] . ', ' . $coordonneesSite['pays']); ?></address></div>
            </div>
            <div class="contact-detail-row">
                <span class="contact-detail-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 6 9 7 9-7"/></svg></span>
                <div><h3><?php echo e($coordonneesSite['email_label']); ?></h3><a href="mailto:<?php echo e($coordonneesSite['email']); ?>"><?php echo e($coordonneesSite['email']); ?></a></div>
            </div>
            <section class="contact-people" aria-labelledby="contacts-title">
                <h3 id="contacts-title"><?php echo e($coordonneesSite['telephone_label']); ?></h3>
                <div class="contact-people-grid">
                    <?php foreach ([1 => 'telephone', 2 => 'telephone_secondaire'] as $numero => $champTelephone) {
                        $nomContact = $coordonneesSite['contact_' . $numero . '_nom'];
                        $roleContact = $coordonneesSite['contact_' . $numero . '_role'];
                        $telephoneContact = $coordonneesSite[$champTelephone];
                        if ($nomContact === '' || $telephoneContact === '') continue;
                    ?>
                        <div class="contact-person">
                            <span class="contact-person-avatar" aria-hidden="true"><?php echo e(contact_initiales($nomContact)); ?></span>
                            <div class="contact-person-identity"><h4><?php echo e($nomContact); ?></h4><?php if ($roleContact !== '') { ?><p><?php echo e($roleContact); ?></p><?php } ?></div>
                            <a href="tel:<?php echo e(contact_telephone_lien($telephoneContact)); ?>" aria-label="<?php echo e('Appeler ' . $nomContact . ' au ' . $telephoneContact); ?>"><?php echo e($telephoneContact); ?><span aria-hidden="true">↗</span></a>
                        </div>
                    <?php } ?>
                </div>
                <?php if ($coordonneesSite['contacts_note'] !== '') { ?><p class="contact-people-note"><?php echo e($coordonneesSite['contacts_note']); ?></p><?php } ?>
            </section>
            <div class="contact-hours">
                <span class="contact-detail-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
                <div><h3><?php echo e($coordonneesSite['horaires_label']); ?></h3>
                    <ul><?php foreach (['horaires_semaine', 'horaires_samedi', 'horaires_complement'] as $champHoraire) { if ($coordonneesSite[$champHoraire] !== '') { ?><li><?php echo e($coordonneesSite[$champHoraire]); ?></li><?php } } ?></ul>
                </div>
            </div>
        </section>

        <section class="contact-message contact-panel" id="nous-ecrire" aria-labelledby="message-title">
            <span class="section-tag"><?php echo e($coordonneesSite['form_surtitre']); ?></span>
            <h2 id="message-title"><?php echo e($coordonneesSite['form_titre']); ?></h2>
            <p class="contact-panel-intro"><?php echo e($coordonneesSite['form_intro']); ?></p>
            <?php if ($succes) { ?><div class="alert alert-success" role="status"><?php echo e($coordonneesSite['succes_message']); ?></div><?php } ?>
            <?php if ($erreurs) { ?><div class="alert alert-error" role="alert"><strong><?php echo e($coordonneesSite['erreur_titre']); ?></strong><ul><?php foreach ($erreurs as $cle => $erreur) { ?><li id="contact-erreur-<?php echo e($cle); ?>"><?php echo e($erreur); ?></li><?php } ?></ul></div><?php } ?>
            <form method="POST" action="contact.php#nous-ecrire" class="contact-form">
                <?php echo csrf_field(); ?>
                <div class="contact-form-row">
                    <div class="contact-field"><label for="contact-nom"><?php echo e($coordonneesSite['nom_label']); ?></label><input id="contact-nom" name="nom" type="text" autocomplete="name" maxlength="100" placeholder="<?php echo e($coordonneesSite['nom_placeholder']); ?>" value="<?php echo e($saisie['nom']); ?>" required<?php if (isset($erreurs['nom'])) { ?> aria-invalid="true" aria-describedby="contact-erreur-nom"<?php } ?>></div>
                    <div class="contact-field"><label for="contact-email"><?php echo e($coordonneesSite['email_form_label']); ?></label><input id="contact-email" name="email" type="email" autocomplete="email" maxlength="150" placeholder="<?php echo e($coordonneesSite['email_placeholder']); ?>" value="<?php echo e($saisie['email']); ?>" required<?php if (isset($erreurs['email'])) { ?> aria-invalid="true" aria-describedby="contact-erreur-email"<?php } ?>></div>
                </div>
                <div class="contact-field"><label for="contact-message"><?php echo e($coordonneesSite['message_label']); ?></label><textarea id="contact-message" name="message" maxlength="5000" rows="5" placeholder="<?php echo e($coordonneesSite['message_placeholder']); ?>" required<?php if (isset($erreurs['message'])) { ?> aria-invalid="true" aria-describedby="contact-erreur-message"<?php } ?>><?php echo e($saisie['message']); ?></textarea></div>
                <div class="contact-submit"><span><?php echo e($coordonneesSite['champs_note']); ?></span><button type="submit" class="btn btn-main"><?php echo e($coordonneesSite['envoi_bouton']); ?> <span aria-hidden="true">↗</span></button></div>
            </form>
            <p class="contact-form-note"><?php echo e($coordonneesSite['form_note']); ?></p>
        </section>
    </div>

    <section class="container contact-access" id="acces" aria-labelledby="access-title">
        <div class="contact-map-header">
            <div><span class="section-tag"><?php echo e($coordonneesSite['carte_surtitre']); ?></span><h2 id="access-title"><?php echo e($coordonneesSite['carte_titre']); ?></h2><p><?php echo e($coordonneesSite['carte_description']); ?></p></div>
            <a class="btn btn-outline" href="<?php echo e(contact_itineraire_url($coordonneesSite)); ?>" target="_blank" rel="noopener noreferrer"><?php echo e($coordonneesSite['itineraire_bouton']); ?> <span aria-hidden="true">↗</span></a>
        </div>
        <iframe class="contact-map" src="<?php echo e(contact_carte_url($coordonneesSite)); ?>" title="<?php echo e($coordonneesSite['carte_accessibilite']); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
    </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
