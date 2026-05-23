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

`voiture` contient les voitures du catalogue.

`marque` contient les marques : BMW, Audi, Mercedes.

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

La page `essai.php` :
1. verifie qu'une voiture est choisie avec `id` dans l'URL ;
2. verifie que le client est connecte ;
3. recupere les informations de la voiture ;
4. recupere les informations du client ;
5. quand le formulaire est envoye :
   - met a jour le client avec telephone/adresse ;
   - ajoute une ligne dans `essai` avec la date et l'heure choisies.

La table `essai` ne stocke que la demande. Les informations personnelles restent dans `client`.

La page `demande_essai.php` permet aussi de faire une demande d'essai, mais avec un formulaire plus complet :
1. le client choisit la voiture dans une liste ;
2. il choisit la date ;
3. il choisit une heure entre 08:00 et 18:00 ;
4. ses informations personnelles sont mises a jour ;
5. la demande est ajoutee dans `essai`.

## Catalogue voitures

La page `voitures.php` :
1. recupere les marques ;
2. pour chaque marque, recupere les voitures associees ;
3. affiche chaque voiture dans une carte ;
4. le bouton detail envoie vers `detail.php?id=...`.

## Detail voiture

La page `detail.php` :
1. recupere l'id de la voiture dans l'URL ;
2. affiche les informations de la voiture ;
3. affiche les images liees dans `voiture_image` ;
4. propose un bouton pour reserver un essai.

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

`header("Location: ...")` sert a rediriger l'utilisateur vers une autre page.
