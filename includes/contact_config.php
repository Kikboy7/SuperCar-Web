<?php
/** Textes et coordonnées partagés entre la page contact, le header et le footer. */
function contact_champs() {
    return [
        'presentation' => ['titre' => 'Présentation de la page', 'aide' => 'Titre, introduction et onglet du navigateur.', 'champs' => [
            'page_titre' => ['label' => 'Titre de l’onglet', 'defaut' => 'Contact', 'type' => 'text', 'max' => 100, 'obligatoire' => true],
            'surtitre' => ['label' => 'Surtitre', 'defaut' => 'À votre écoute', 'type' => 'text', 'max' => 100, 'obligatoire' => true],
            'titre' => ['label' => 'Titre principal (H1)', 'defaut' => 'Parlons de votre prochain projet.', 'type' => 'text', 'max' => 140, 'obligatoire' => true],
            'introduction' => ['label' => 'Introduction', 'defaut' => 'Un véhicule vous intéresse ? Une question sur nos services ? Notre équipe vous accompagne, du premier échange à la livraison.', 'type' => 'textarea', 'max' => 700, 'obligatoire' => true],
        ]],
        'coordonnees' => ['titre' => 'Coordonnées partagées', 'aide' => 'Les coordonnées et les horaires sont partagés avec l’en-tête et le pied de page du site.', 'champs' => [
            'coordonnees_titre' => ['label' => 'Titre du bloc de coordonnées', 'defaut' => 'Rencontrons-nous', 'type' => 'text', 'max' => 120, 'obligatoire' => true],
            'coordonnees_intro' => ['label' => 'Présentation des coordonnées', 'defaut' => 'En concession, par téléphone ou par e-mail : échangeons comme vous le souhaitez.', 'type' => 'textarea', 'max' => 400, 'obligatoire' => true],
            'adresse_label' => ['label' => 'Libellé de l’adresse', 'defaut' => 'Notre adresse', 'type' => 'text', 'max' => 80, 'obligatoire' => true],
            'adresse' => ['label' => 'Adresse', 'defaut' => '58–60, Cybercité', 'type' => 'text', 'max' => 200, 'obligatoire' => true],
            'ville' => ['label' => 'Ville', 'defaut' => 'Ébène', 'type' => 'text', 'max' => 100, 'obligatoire' => true],
            'pays' => ['label' => 'Pays', 'defaut' => 'Île Maurice', 'type' => 'text', 'max' => 100, 'obligatoire' => true],
            'telephone_label' => ['label' => 'Libellé du téléphone', 'defaut' => 'Appelez-nous', 'type' => 'text', 'max' => 80, 'obligatoire' => true],
            'telephone' => ['label' => 'Téléphone principal', 'defaut' => '+230 212 0000', 'type' => 'tel', 'max' => 40, 'obligatoire' => true],
            'telephone_secondaire' => ['label' => 'Téléphone secondaire', 'defaut' => '+230 5250 0000', 'type' => 'tel', 'max' => 40, 'obligatoire' => false],
            'email_label' => ['label' => 'Libellé de l’e-mail', 'defaut' => 'Écrivez-nous', 'type' => 'text', 'max' => 80, 'obligatoire' => true],
            'email' => ['label' => 'Adresse e-mail', 'defaut' => 'contact@supercar.mu', 'type' => 'email', 'max' => 150, 'obligatoire' => true],
            'horaires_label' => ['label' => 'Libellé des horaires', 'defaut' => 'Nos horaires', 'type' => 'text', 'max' => 80, 'obligatoire' => true],
            'horaires_semaine' => ['label' => 'Horaires en semaine', 'defaut' => 'Lun. – ven. : 08 h – 18 h', 'type' => 'text', 'max' => 150, 'obligatoire' => true],
            'horaires_samedi' => ['label' => 'Horaires du samedi', 'defaut' => 'Samedi : 09 h – 13 h', 'type' => 'text', 'max' => 150, 'obligatoire' => true],
            'horaires_complement' => ['label' => 'Horaires complémentaires', 'defaut' => '', 'type' => 'text', 'max' => 150, 'obligatoire' => false],
            'visite_note' => ['label' => 'Conseil pour la visite', 'defaut' => 'Pour un essai, pensez à réserver votre créneau.', 'type' => 'textarea', 'max' => 300, 'obligatoire' => true],
            'visite_bouton' => ['label' => 'Texte du lien vers les essais', 'defaut' => 'Demande d’essais', 'type' => 'text', 'max' => 80, 'obligatoire' => true],
        ]],
        'formulaire' => ['titre' => 'Formulaire de contact', 'aide' => 'Tous les textes du formulaire sont modifiables ; les champs et les protections restent en place.', 'champs' => [
            'form_surtitre' => ['label' => 'Surtitre du formulaire', 'defaut' => 'Écrivez-nous', 'type' => 'text', 'max' => 80, 'obligatoire' => true],
            'form_titre' => ['label' => 'Titre du formulaire (H2)', 'defaut' => 'Comment pouvons-nous vous aider ?', 'type' => 'text', 'max' => 140, 'obligatoire' => true],
            'form_intro' => ['label' => 'Introduction du formulaire', 'defaut' => 'Présentez-nous votre projet. Nous prendrons le temps de vous répondre.', 'type' => 'textarea', 'max' => 400, 'obligatoire' => true],
            'nom_label' => ['label' => 'Libellé du nom', 'defaut' => 'Nom complet', 'type' => 'text', 'max' => 80, 'obligatoire' => true],
            'nom_placeholder' => ['label' => 'Exemple pour le nom', 'defaut' => 'Votre nom et votre prénom', 'type' => 'text', 'max' => 100, 'obligatoire' => true],
            'email_form_label' => ['label' => 'Libellé de l’e-mail', 'defaut' => 'Adresse e-mail', 'type' => 'text', 'max' => 80, 'obligatoire' => true],
            'email_placeholder' => ['label' => 'Exemple pour l’e-mail', 'defaut' => 'vous@exemple.com', 'type' => 'text', 'max' => 100, 'obligatoire' => true],
            'message_label' => ['label' => 'Libellé du message', 'defaut' => 'Votre message', 'type' => 'text', 'max' => 80, 'obligatoire' => true],
            'message_placeholder' => ['label' => 'Exemple pour le message', 'defaut' => 'Parlez-nous du véhicule ou du service qui vous intéresse…', 'type' => 'text', 'max' => 200, 'obligatoire' => true],
            'champs_note' => ['label' => 'Indication des champs obligatoires', 'defaut' => 'Tous les champs sont obligatoires.', 'type' => 'text', 'max' => 150, 'obligatoire' => true],
            'envoi_bouton' => ['label' => 'Texte du bouton d’envoi', 'defaut' => 'Envoyer mon message', 'type' => 'text', 'max' => 80, 'obligatoire' => true],
            'form_note' => ['label' => 'Note sous le formulaire', 'defaut' => 'Votre message est enregistré pour permettre à l’équipe SuperCar de vous répondre.', 'type' => 'textarea', 'max' => 350, 'obligatoire' => true],
            'succes_message' => ['label' => 'Confirmation après l’envoi', 'defaut' => 'Merci ! Votre message a bien été enregistré. Notre équipe vous répondra rapidement.', 'type' => 'textarea', 'max' => 400, 'obligatoire' => true],
        ]],
        'carte' => ['titre' => 'Carte et accès', 'aide' => 'Le lieu recherché définit le point de la carte et le lien d’itinéraire, sans saisir de code HTML.', 'champs' => [
            'carte_surtitre' => ['label' => 'Surtitre de la carte', 'defaut' => 'Venez nous rencontrer', 'type' => 'text', 'max' => 100, 'obligatoire' => true],
            'carte_titre' => ['label' => 'Titre du bloc carte (H2)', 'defaut' => 'Votre prochaine étape : SuperCar', 'type' => 'text', 'max' => 140, 'obligatoire' => true],
            'carte_description' => ['label' => 'Description de l’accès', 'defaut' => 'Retrouvez-nous au cœur de la Cybercité, à Ébène.', 'type' => 'textarea', 'max' => 400, 'obligatoire' => true],
            'carte_recherche' => ['label' => 'Point de la carte (adresse ou coordonnées)', 'defaut' => '-20.2463642,57.4892986', 'type' => 'text', 'max' => 250, 'obligatoire' => true],
            'carte_zoom' => ['label' => 'Niveau de zoom', 'defaut' => '16', 'type' => 'number', 'max' => 2, 'obligatoire' => true],
            'carte_accessibilite' => ['label' => 'Titre accessible de la carte', 'defaut' => 'Localisation de SuperCar à Ébène, Île Maurice', 'type' => 'text', 'max' => 180, 'obligatoire' => true],
            'itineraire_bouton' => ['label' => 'Texte du lien d’itinéraire', 'defaut' => 'Préparer mon itinéraire', 'type' => 'text', 'max' => 100, 'obligatoire' => true],
        ]],
        'messages' => ['titre' => 'Messages de validation', 'aide' => 'Les limites techniques restent fixées à 100 caractères pour le nom, 150 pour l’e-mail et 5 000 pour le message.', 'champs' => [
            'erreur_titre' => ['label' => 'Titre du bloc d’erreur', 'defaut' => 'Votre message n’a pas été envoyé.', 'type' => 'text', 'max' => 140, 'obligatoire' => true],
            'erreur_session' => ['label' => 'Erreur de session', 'defaut' => 'Votre session a expiré. Veuillez réessayer.', 'type' => 'text', 'max' => 200, 'obligatoire' => true],
            'erreur_nom' => ['label' => 'Erreur du nom', 'defaut' => 'Saisissez votre nom complet (100 caractères maximum).', 'type' => 'text', 'max' => 200, 'obligatoire' => true],
            'erreur_email' => ['label' => 'Erreur de l’e-mail', 'defaut' => 'Saisissez une adresse e-mail valide.', 'type' => 'text', 'max' => 200, 'obligatoire' => true],
            'erreur_message' => ['label' => 'Erreur du message', 'defaut' => 'Saisissez votre message (5 000 caractères maximum).', 'type' => 'text', 'max' => 200, 'obligatoire' => true],
        ]],
    ];
}

function contact_defauts() {
    $valeurs = [];
    foreach (contact_champs() as $groupe) {
        foreach ($groupe['champs'] as $cle => $champ) {
            $valeurs[$cle] = $champ['defaut'];
        }
    }
    return $valeurs;
}

function contact_charger(PDO $pdo) {
    $valeurs = contact_defauts();
    try {
        foreach ($pdo->query('SELECT cle, valeur FROM contenu_contact')->fetchAll(PDO::FETCH_ASSOC) as $ligne) {
            if (array_key_exists($ligne['cle'], $valeurs)) {
                $valeurs[$ligne['cle']] = $ligne['valeur'];
            }
        }
    } catch (PDOException $exception) {
        // Un ancien clone reste consultable avant l’import de la migration.
        if (($exception->errorInfo[1] ?? null) !== 1146) {
            throw $exception;
        }
    }
    return $valeurs;
}

function contact_telephone_lien($telephone) {
    return preg_replace('/[^0-9+]/', '', $telephone);
}

function contact_carte_url(array $contact) {
    $zoom = max(3, min(20, (int) $contact['carte_zoom']));
    return 'https://maps.google.com/maps?q=' . rawurlencode($contact['carte_recherche']) . '&z=' . $zoom . '&output=embed';
}

function contact_itineraire_url(array $contact) {
    return 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($contact['carte_recherche']);
}
