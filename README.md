# SuperCar-Web

SuperCar-Web est un projet BTS SIO de site web dynamique pour un concessionnaire automobile premium.

Le site est developpe en PHP avec une base de donnees MySQL. Il contient une partie client et une partie administrateur.

## Fonctionnalites

### Partie client

- Consultation du catalogue de voitures
- Affichage du detail d'une voiture
- Creation de compte client
- Connexion et deconnexion client
- Demande d'essai avec date et heure
- Consultation des demandes d'essai du client connecte
- Formulaire de contact
- Affichage des services proposes

### Partie administrateur

- Connexion admin separee de la connexion client
- Tableau de bord avec compteurs
- Gestion des voitures
- Gestion des demandes d'essai
- Modification du statut d'une demande : en attente, valide, refuse
- Consultation et suppression des messages
- Gestion des services

## Technologies utilisees

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
mcd/         Documents de modelisation de la base
table.sql    Script SQL principal de creation de la base
```

## Base de donnees

La base de donnees s'appelle `supercar`.

Les tables principales sont :

- `client` : informations personnelles des clients
- `login` : identifiants et mots de passe des clients
- `admin` : compte administrateur
- `marque` : marques des voitures
- `voiture` : voitures du catalogue
- `voiture_image` : images supplementaires des voitures
- `essai` : demandes d'essai
- `message` : messages envoyes depuis la page contact
- `services` : services proposes par SuperCar

## Installation en local

1. Placer le dossier du projet dans le dossier `www` de WampServer.
2. Demarrer Apache et MySQL avec WampServer.
3. Ouvrir phpMyAdmin.
4. Importer le fichier `table.sql`.
5. Verifier la connexion dans `includes/db.php`.
6. Ouvrir le site dans le navigateur :

```text
http://localhost/SiteWeb%20Supercar/
```

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

- Les mots de passe sont proteges avec `password_hash`.
- Les connexions sont verifiees avec `password_verify`.
- Les sessions distinguent les clients et les administrateurs.
- Les requetes preparees avec `prepare()` et `execute()` limitent les risques d'injection SQL.
- Les demandes d'essai sont liees au client connecte et a la voiture choisie.
- Les horaires d'essai sont limites a des creneaux fixes de 08:00 a 18:00.

## Auteur

Projet realise dans le cadre du BTS SIO.
