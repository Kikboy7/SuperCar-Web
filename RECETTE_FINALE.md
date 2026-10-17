# Recette finale SuperCar — 17 octobre 2026

## Résultat et état de livraison

Les corrections sont présentes dans le projet SuperCar et ce rapport accompagne leur livraison. L’état de publication est vérifiable dans l’historique Git du dépôt : ne pas confondre la préparation locale avec la validation sur l’hébergeur réel.

La configuration privée et les fichiers de recette contenant des identifiants sont exclus de Git. Le paquet de déploiement et les deux imports publics sont fournis séparément.

## Contrôles réellement exécutés

- 441 contrôles de recette automatisés réussis sur une base jetable `supercar_recette_...`, sans copie des comptes du projet. Ils couvrent les pages publiques, les 21 fiches voiture et les 8 fiches service, les images avec respect de la casse Linux, l’inscription, les mots de passe, les doublons de comptes, la connexion, le profil, les demandes d’essai valides/invalides et répétées, les messages, l’administration et les contraintes de suppression d’une voiture liée à un essai.
- Ajout, modification et suppression de voitures et services fictifs testés ; changement des trois statuts d’essai ; réponse interne et suppression de message ; modification puis restauration des textes d’accueil et coordonnées sur la seule base de recette.
- 41 contrôles supplémentaires du catalogue réussis : recherche marque/modèle combinée, accents, budget, tous les tris, filtres combinés, pagination, retrait d’un critère, anciennes URL de marque et paramètres malformés.
- Parcours de 71 URL et vérification de 149 ressources locales : aucun lien interne, ancre ou fichier manquant détecté.
- Huit protections HTTP vérifiées sur Apache Wamp : SQL, configuration privée, dépôt Git, includes et données de recette refusés (403).
- Installation du nouveau schéma vide puis import du contenu public réussis sur la base isolée ; export public final vérifié dans une transaction annulée, sans remplacer le contenu de la base du projet.
- Vérifications visuelles des modèles de pages publiques et des sept rubriques administrateur ; mesures responsive à 390 px sans débordement horizontal sur les pages contrôlées, en clair et sombre côté public. Le contrôle connecté client au format mobile n’a pas pu être terminé après l’interruption des outils.
- Dans le navigateur : menu mobile et Échap, persistance du thème, galerie au clavier, inscription, connexion, onglets au clavier, modification du profil avec retour sur le bon onglet et envoi d’un essai futur.
- Mode production : compte au mot de passe public `admin123` refusé, administrateur privé accepté, onzième échec de connexion bloqué en HTTP 429 même avec des sessions différentes. Panne de base simulée : HTTP 503, sans détails techniques exposés.
- Paquet généré : 191 fichiers, aucun fichier privé, SQL, `.git/` ou `tests/` dans le ZIP ; les protections et fichiers de configuration d’exemple sont présents. Les 34 fichiers PHP du ZIP, extraits et analysés sur disque, ne présentent aucune erreur de syntaxe. Les imports SQL sont fournis séparément.

Les textes publics du catalogue, des services et de l’accueil ont été relus. Les dernières valeurs saisies dans l’administration ont été conservées dans l’export public, y compris les noms propres personnalisés.

## Corrections apportées

- Traitement des formulaires et redirections avant l’envoi du HTML ; titres des fiches désormais définis avant le header.
- Validation serveur des coordonnées, dates réelles et heures futures, prix, statuts, tailles de champs et noms de fichiers image ; neutralisation des tableaux et encodages invalides.
- Redirection après inscription, profil, essai et opérations administrateur pour éviter les répétitions à l’actualisation ; contrôle des demandes d’essai déjà présentes.
- Déconnexion POST avec CSRF, cookie de session propre à SuperCar, HttpOnly/SameSite, Secure configurable pour HTTPS, session stricte et vérification de l’existence de l’administrateur.
- Limitation des échecs d’authentification par IP et par zone en production ; refus du mot de passe administrateur de démonstration en production.
- Configuration de base séparée du code et ignorée par Git ; erreurs techniques masquées en production ; en-têtes de sécurité et protections Apache.
- Libellés persistants des formulaires, lien d’évitement, galerie commandable au clavier, onglets accessibles et correction de l’initiale des prénoms accentués.
- Schéma d’installation sans suppression de base ni compte prédéfini, outil CLI de création d’administrateur, export public actualisé et guide d’hébergement.

## Fichiers de livraison et précautions

Le paquet d’hébergement est produit sans configuration privée, SQL, dossier Git ou données de recette. Les imports `schema.sql` et `contenu_site.sql` sont fournis séparément. Le guide `HEBERGEMENT.md` décrit la configuration et les étapes nécessaires.

Ne pas importer l’ancien `table.sql` sur une base à conserver : il est destructif. Ne pas publier `config.local.php`, les identifiants de recette ou les journaux. Les réponses aux messages sont stockées en base : le site n’envoie pas d’e-mails automatiquement.

## Ce qui reste obligatoire avant ouverture publique

1. Terminer le contrôle mobile connecté client interrompu lors de la précédente session. Les bases jetables et fichiers `.audit-*` sont identifiés localement dans `tests/.audit-state.json` ; ils ne sont ni exportés dans le contenu public ni versionnés. Ne supprimer aucune base utilisateur.
2. Configurer sur l’hébergeur les identifiants SQL privés, HTTPS et les cookies Secure, puis créer un véritable compte administrateur privé.
3. Rejouer un parcours complet sur l’URL réelle et vérifier les interdictions HTTP, les journaux privés et les sauvegardes/restaurations.

Cette recette locale n’est pas une garantie d’absence totale de bugs, un audit de sécurité indépendant ou une validation de l’environnement d’un hébergeur encore non choisi. Les droits d’exploitation commerciale des visuels et les obligations d’une collecte réelle de données ne sont pas certifiés. Les coordonnées actuelles sont fictives et adaptées à une démonstration pédagogique.
