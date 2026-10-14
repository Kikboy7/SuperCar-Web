# SuperCar-Web

SuperCar-Web est un projet BTS SIO de site web dynamique pour un concessionnaire automobile premium.

Le site est développé en PHP avec une base de données MySQL. Il contient une partie client et une partie administrateur.

## Fonctionnalités

### Partie client

- Consultation du catalogue de voitures avec recherche, filtre par marque et pagination (7 marques, 21 voitures)
- Accueil : six modèles phares de six marques distinctes, avec remplacement automatique si un modèle sélectionné n’est plus au catalogue
- Affichage du détail d’une voiture (galerie d’images et caractéristiques techniques)
- Création de compte client
- Connexion et déconnexion client
- Demande d'essai avec date et heure
- Espace « Mon compte » : profil, modification des coordonnées et consultation des demandes d'essai (avec statut)
- Carrousel automatique d’images de fond dans la bannière de la page d’accueil
- Page contact responsive, carte pleine largeur à Ébène et formulaire accessible
- Affichage des services proposés

### Partie administrateur

- Connexion admin séparée de la connexion client
- Tableau de bord avec compteurs
- Gestion des voitures
- Gestion des demandes d'essai
- Modification du statut d'une demande : en attente, validée, refusée
- Consultation et suppression des messages
- Gestion des services
- Gestion des textes de la page d’accueil
- Gestion de la page contact : 46 paramètres (titres, textes, coordonnées, horaires, carte, formulaire et confirmations)
- Synchronisation des coordonnées du header et du footer

## Technologies utilisées

- PHP
- MySQL
- HTML
- CSS
- Bootstrap
- WampServer

## Structure du projet

```text
admin/       Pages du back-office administrateur
includes/    Fichiers communs comme la connexion BDD, le header et le footer
images/      Images du site et des voitures
mcd/         Documents de modélisation de la base
table.sql    Script SQL principal de création de la base
```

## Base de données

La base de données s'appelle `supercar`.

Les tables principales sont :

- `client` : informations personnelles des clients
- `login` : identifiants et mots de passe des clients
- `admin` : compte administrateur
- `marque` : marques des voitures (BMW, Audi, Mercedes, Porsche, Ferrari, Jeep, Land Rover)
- `voiture` : voitures du catalogue
- `voiture_image` : images supplémentaires des voitures (6 par voiture)
- `essai` : demandes d'essai
- `message` : messages envoyés depuis la page contact
- `services` : services proposés par SuperCar
- `contenu_accueil` : textes de la page d’accueil
- `contenu_contact` : paramètres de la page contact et coordonnées partagées

## Installation en local

1. Placer le dossier du projet dans le dossier `www` de WampServer.
2. Démarrer Apache et MySQL avec WampServer.
3. Ouvrir phpMyAdmin.
4. Importer le fichier `table.sql`.
5. Importer `contenu_site.sql` pour retrouver le catalogue, les huit services et les textes actuels du site.
6. Vérifier la connexion dans `includes/db.php`.
7. Ouvrir le site dans le navigateur :

```text
http://localhost/SiteWeb%20Supercar/
```

## Mise à jour d’une installation existante

`contenu_site.sql` est un instantané du contenu public du site au 14 octobre 2026 : catalogue, services, accueil et coordonnées. Il ne contient aucun compte, mot de passe, message privé ou réservation. Après les migrations de structure nécessaires, son import remplace les valeurs des contenus correspondants : sauvegardez les contenus personnalisés du PC cible avant de l’importer.

Ne réimportez pas `table.sql` sur une base contenant des données à conserver : ce script recrée entièrement la base. Pour cette évolution, importez uniquement `migration_contact.sql` puis `migration_orthographe.sql` dans phpMyAdmin. Les migrations ajoutent les paramètres manquants et appliquent des corrections ciblées sans supprimer de données ni écraser les coordonnées déjà personnalisées.

La nouvelle rubrique **Page contact** de l’administration permet de modifier les textes, les coordonnées, les horaires et le point de la carte. Le point peut être une adresse ou des coordonnées géographiques ; aucun code HTML d’iframe n’est requis. Les téléphones, l’e-mail et le zoom sont contrôlés avant la sauvegarde, réalisée dans une transaction.

Les données saisies dans ce formulaire sont stockées dans `message` et consultables dans l’administration ; le site n’envoie pas automatiquement d’e-mails. L’envoi utilise une redirection après POST pour empêcher les doublons à l’actualisation.

## Connexion administrateur de test

Identifiant :

```text
admin
```

Mot de passe :

```text
admin123
```

Ce compte sert uniquement aux tests en local.

## Points importants du projet

- Les mots de passe sont protégés avec `password_hash`.
- Les connexions sont vérifiées avec `password_verify`.
- Les sessions distinguent les clients et les administrateurs.
- L'identifiant de session est régénéré après la connexion (`session_regenerate_id`).
- Les requêtes préparées avec `prepare()` et `execute()` limitent les risques d'injection SQL.
- Toutes les données affichées sont échappées avec `htmlspecialchars` (fonction `e()`).
- Les formulaires et actions sensibles sont protégés par un jeton CSRF.
- Les demandes d’essai sont liées au client connecté et à la voiture choisie.
- Les horaires d’essai sont limités à des créneaux fixes de 08 h à 18 h.
- Les caractéristiques des voitures (année, carburant, boîte, puissance) sont stockées en base.
- La page « Mon compte » (`compte.php`) regroupe profil et réservations ; `reservation.php` y redirige.
- La bannière de l’accueil utilise un carrousel automatique, sans commandes manuelles. L’animation respecte la préférence de réduction des mouvements.
- Le catalogue propose 7 marques et 21 voitures ; chaque voiture dispose de 6 photos défilables en JavaScript sur la page de détail.

## Auteur

Projet réalisé dans le cadre du BTS SIO.
