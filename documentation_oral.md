# Documentation orale - SuperCar-Web

Ce document sert a expliquer simplement le fonctionnement du projet.

## Idee generale

SuperCar-Web est un site PHP/MySQL pour un concessionnaire automobile.

Le projet a deux parties :
- la partie client, accessible aux visiteurs et clients ;
- la partie admin, reservee a l'administrateur.

## Tables principales

`client` contient les informations personnelles :
- nom ;
- email ;
- telephone ;
- adresse.

`login` contient les informations de connexion client :
- identifiant ;
- mot de passe hash ;
- id_client.

`voiture` contient les voitures du catalogue :
- modele ;
- prix ;
- description ;
- image ;
- annee ;
- carburant ;
- boite ;
- puissance ;
- id_marque.

`marque` contient les marques : BMW, Audi, Mercedes, Porsche, Ferrari, Jeep, Land Rover.

`voiture_image` contient les images supplementaires de chaque voiture.

`essai` contient les demandes d'essai :
- date_essai ;
- heure_essai ;
- statut ;
- id_client ;
- id_voiture.

`message` contient les messages envoyes depuis la page contact.
Un message peut aussi contenir :
- id_client, si le message vient d'un client connecte ;
- statut_message ;
- reponse_admin ;
- date_reponse.

`services` contient les services affiches sur la page services.

`admin` contient les comptes administrateurs.

## Catalogue

Le catalogue compte 7 marques et 21 voitures (3 par marque).
Chaque voiture possede 6 images dans la table `voiture_image`, affichees dans
une galerie avec des fleches sur la page detail. Les images ont ete telechargees
depuis Wikimedia Commons (licence libre CC) et les caracteristiques (prix,
annee, carburant, puissance, description) sont fictives.

Marques du catalogue :
- BMW : M3 Competition, Serie 7, M8 Competition ;
- Audi : RS6 Avant, RS3 Sportback, RS7 Sportback ;
- Mercedes : AMG GT, Classe G, C 63 AMG ;
- Porsche : 911 Turbo S, Cayenne Turbo GT, Taycan Turbo S ;
- Ferrari : F8 Tributo, Roma, Purosangue ;
- Jeep : Wrangler Rubicon, Grand Cherokee, Compass ;
- Land Rover : Range Rover Sport, Defender 110, Evoque.

La page catalogue (voitures.php) permet de filtrer par marque et de rechercher ;
les resultats sont pagines (6 par page). La page detail (detail.php) affiche le
carrousel d'images et les specifications completes.

## Connexion client

La page `login.php` :
1. recoit un identifiant et un mot de passe ;
2. cherche cet identifiant dans la table `login` ;
3. verifie le mot de passe avec `password_verify` ;
4. garde l'id du client dans `$_SESSION['client']`.

La session permet de savoir sur les autres pages si un client est connecte.

## Inscription client

La page `register.php` :
1. verifie les champs du formulaire ;
2. verifie que l'identifiant et l'email ne sont pas deja utilises ;
3. cree une ligne dans `client` ;
4. cree une ligne dans `login` avec le mot de passe hash ;
5. relie les deux tables avec `id_client`.

## Demande d'essai

La page `demande_essai.php` est la page principale pour faire une demande d'essai :
1. verifie que le client est connecte ;
2. le client choisit la voiture dans une liste (ou elle est preselectiionnee depuis la fiche voiture) ;
3. il choisit la date (aujourd'hui ou dans le futur) ;
4. il choisit une heure entre 08:00 et 18:00 ;
5. ses informations personnelles (telephone, adresse) sont mises a jour ;
6. la demande est ajoutee dans la table `essai`.

Les operations 5 et 6 sont faites dans une transaction : si l'une echoue, aucune n'est enregistree.

L'ancienne page `essai.php` a ete remplacee : elle redirige maintenant simplement vers `demande_essai.php` en conservant la voiture choisie dans l'URL. Cela evite de dupliquer le code de la demande d'essai.

La table `essai` ne stocke que la demande. Les informations personnelles restent dans `client`.

## Mon compte

La page `compte.php` est l'espace client central :
1. si le visiteur n'est pas connecte, elle le redirige vers `login.php` ;
2. si le client est connecte, elle affiche son profil (nom, email, telephone, adresse) ;
3. elle affiche ses demandes d'essai avec leur statut ;
4. elle permet de modifier son profil (nom, telephone, adresse) via un formulaire.

La page est decoupee en deux onglets (sans rechargement de page) :
- « Mes reservations » : la liste des demandes d'essai du client ;
- « Modifier mon profil » : le formulaire de mise a jour des coordonnees.

Les onglets utilisent du JavaScript simple : au clic, on cache tous les panneaux
puis on affiche celui correspondant (`data-panel` / `id`).

L'ancienne page `reservation.php` redirige vers `compte.php` : les reservations
sont desormais affichees dans l'espace compte pour eviter la duplication de code.

Dans le menu (header), le bouton « Mon compte » remplace les anciens boutons
« Connexion » et « S'inscrire ». Quand le client est connecte, il affiche son
prenom et un bouton de deconnexion.

## Carrousel du hero (accueil)

La page d'accueil affiche un carrousel d'images de fond dans le hero :
1. les images (`background1.webp`, `background2.png`, `background3.png`, `background4.png`)
   sont des calques superposes dans `.hero-slider` ;
2. une seule image est visible a la fois (classe `is-active`) ;
3. un petit script JavaScript change l'image toutes les 6 secondes, avec des
   fleches et des points de navigation cliquables ;
4. un voile sombre (CSS) est place au-dessus des images pour garder le texte lisible.

## Catalogue voitures

La page `voitures.php` :
1. permet une recherche par mot-cle (modele, marque, description) ;
2. permet de filtrer par marque ;
3. affiche les voitures par groupe de 6 avec une pagination dynamique ;
4. la pagination utilise `LIMIT` et `OFFSET` dans la requete SQL ;
5. chaque carte renvoie vers `detail.php?id=...`.

## Detail voiture

La page `detail.php` :
1. recupere l'id de la voiture dans l'URL ;
2. verifie que la voiture existe (sinon retour au catalogue) ;
3. affiche les informations de la voiture ;
4. affiche les caracteristiques techniques (annee, carburant, boite, puissance) stockees en base ;
5. affiche les images liees dans `voiture_image` avec une galerie cliquable ;
6. propose un bouton pour reserver un essai.

## Contact

La page `contact.php` :
1. affiche les informations de contact ;
2. enregistre le message du visiteur dans la table `message` ;
3. enregistre aussi `id_client` si le message vient d'un client connecte.

## Services

La page `services.php` :
1. recupere les services depuis la table `services` ;
2. affiche les services sous forme de cartes.

## Connexion admin

La page `admin/login.php` :
1. recoit l'identifiant et le mot de passe admin ;
2. cherche l'identifiant dans la table `admin` ;
3. verifie le mot de passe avec `password_verify` ;
4. garde l'id admin dans `$_SESSION['admin']` ;
5. redirige vers `dashboard.php`.

Compte admin de depart pour les tests :
- identifiant : `admin` ;
- mot de passe : `admin123`.

Ce mot de passe est stocke en base sous forme de hash, pas en clair.

## Pages admin

Toutes les pages admin utilisent `admin/includes/auth.php`.

`auth.php` sert a proteger les pages :
1. il demarre la session ;
2. il inclut la connexion a la base ;
3. il verifie que `$_SESSION['admin']` existe ;
4. sinon il redirige vers `admin/login.php`.

`admin/includes/header.php` contient le menu admin et le style clair du back-office.

`admin/dashboard.php` affiche des compteurs :
- nombre de voitures ;
- nombre de demandes d'essai ;
- nombre de messages ;
- nombre de services.

`admin/essais.php` permet de voir les demandes d'essai.
L'administrateur peut changer le statut :
- en attente ;
- valide ;
- refuse.

`admin/messages.php` affiche les messages envoyes depuis la page contact.
L'administrateur peut :
- voir si le message vient d'un visiteur ou d'un client connecte ;
- changer le statut du message ;
- enregistrer une reponse ou une note ;
- repondre par email ;
- supprimer un message.

`admin/services.php` permet :
- d'ajouter un service ;
- de modifier un service ;
- de supprimer un service.

`admin/voitures.php` permet :
- d'ajouter une voiture ;
- de modifier une voiture ;
- de supprimer une voiture.

Pour les voitures, l'image est geree simplement avec le nom du fichier image.
Les images doivent se trouver dans le dossier `images`.

`admin/logout.php` supprime la session admin et renvoie vers la page de connexion admin.

## Points importants a expliquer

`password_hash` sert a enregistrer un mot de passe de maniere securisee.

`password_verify` sert a verifier un mot de passe saisi avec le mot de passe hash en base.

`$_SESSION` sert a retenir qu'un utilisateur est connecte.

Les requetes preparees avec `prepare()` et `execute()` servent a eviter les injections SQL.

`session_regenerate_id()` change l'identifiant de session apres la connexion pour eviter la fixation de session.

`htmlspecialchars()` (via la fonction `e()` dans `includes/functions.php`) sert a echapper les donnees affichees pour eviter les failles XSS.

Le jeton CSRF (`csrf_token`, `csrf_field`, `csrf_verify`) est un code secret genere dans la session : il est insere dans chaque formulaire et verifie cote serveur. Cela empeche un tiers d'envoyer un formulaire a la place d'un utilisateur connecte.

`header("Location: ...")` sert a rediriger l'utilisateur vers une autre page.

`LIMIT` et `OFFSET` dans une requete SQL permettent d'afficher les resultats par page (pagination).
