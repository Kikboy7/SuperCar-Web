# Mise en ligne de SuperCar

Ce projet pédagogique a été vérifié localement le 17 octobre 2026. La validation du domaine, du certificat HTTPS et de la configuration finale doit être faite sur le serveur choisi. Les coordonnées et les interlocuteurs actuels sont fictifs. Utiliser des données fictives pour la démonstration.

## Prérequis

- PHP 8.2 ou plus récent, avec `pdo_mysql`, `mbstring` et les sessions. Recette effectuée sous PHP 8.4.15.
- MySQL 8+ ou MariaDB 10.6+, tables InnoDB et encodage `utf8mb4`.
- Apache 2.4 acceptant les fichiers `.htaccess`. Pour Nginx, faire reproduire par l’hébergeur les interdictions des fichiers internes : les `.htaccess` n’y sont pas lus.
- HTTPS obligatoire. Le dossier temporaire PHP doit être accessible en écriture pour la limitation des connexions ; le reste du code peut être en lecture seule.
- Sauvegardes privées de la base et du code, non accessibles par une URL.

## 1. Déposer les fichiers

Le paquet de déploiement contient le code public, l’administration, les fichiers communs et les images. Ne pas envoyer `.git/`, `tests/`, les journaux ou la configuration du PC local. Conserver les fichiers `.htaccess`, y compris dans `includes/`, `admin/includes/`, `images/` et `tools/`.

La page d’entrée est `index.php`. Les liens relatifs fonctionnent aussi dans un sous-dossier. Si l’hébergeur refuse une directive Apache et renvoie HTTP 500, faire adapter la configuration avec lui ; ne pas retirer simplement les protections des fichiers privés.

## 2. Installer la base dans une base NEUVE et vide

Créer la base et son utilisateur dans le panneau de l’hébergeur. Dans phpMyAdmin, sélectionner cette base, puis importer dans cet ordre :

1. `schema.sql` : les 11 tables, sans compte prédéfini et sans suppression de base.
2. `contenu_site.sql` : catalogue, services et textes publics.

Ces deux fichiers n’imposent pas le nom `supercar` : le nom préfixé fourni par l’hébergeur convient. Le contenu public ne contient aucun compte, réservation ou message privé. Il peut écraser les textes et modèles portant les mêmes identifiants : ne pas l’importer sur une installation personnalisée sans sauvegarde.

**Ne pas utiliser `table.sql` en production : ce script historique de démonstration supprime et recrée la base `supercar` et ajoute un compte de test.** Les anciennes migrations ciblent également `supercar` : elles servent à une ancienne installation locale, pas à l’installation neuve décrite ici.

L’utilisateur SQL utilisé par le site a seulement besoin de lire, ajouter, modifier et supprimer les données de cette base ; pas de créer des bases ou de modifier le schéma au quotidien.

## 3. Configurer le serveur sans publier de secrets

Copier `config.example.php` en `config.local.php` sur le serveur, puis renseigner les valeurs de l’hébergeur :

- `env` : `production` ;
- `db_host`, `db_port`, `db_name`, `db_user`, `db_password` ;
- `timezone` : `Indian/Mauritius` ;
- `cookie_secure` : `true` pour le site HTTPS, y compris derrière un proxy TLS.

Ce fichier est ignoré par Git et interdit d’accès HTTP. Ses permissions doivent limiter sa lecture au compte du serveur. Ne jamais y copier les identifiants `root` sans mot de passe du PC Wamp.

Les variables d’environnement `SUPERCAR_ENV`, `SUPERCAR_DB_HOST`, `SUPERCAR_DB_PORT`, `SUPERCAR_DB_NAME`, `SUPERCAR_DB_USER`, `SUPERCAR_DB_PASSWORD`, `SUPERCAR_TIMEZONE` et `SUPERCAR_COOKIE_SECURE` sont prioritaires. Un fichier `.env` n’est pas chargé automatiquement : utiliser la configuration du panneau ou `config.local.php`.

Les erreurs techniques sont journalisées côté serveur, pas affichées au visiteur en production. Une panne de connexion à la base renvoie une page générique HTTP 503.

## 4. Créer SON administrateur privé

L’installation neuve ne crée aucun administrateur. Le compte historique au mot de passe `admin123` est refusé en production, même s’il a été importé par erreur.

Avec un terminal sur le serveur, définir temporairement `SUPERCAR_ADMIN_PASSWORD` avec un mot de passe privé (12 caractères minimum, 72 octets maximum), puis lancer :

```text
php tools/creer-admin.php identifiant_prive
```

L’outil crée ou met à jour ce compte. Retirer la variable immédiatement après utilisation. Ne pas mettre le mot de passe dans Git, dans une commande partagée ou dans un ticket public.

Sans terminal chez l’hébergeur, lancer localement l’outil avec `--hash` pour produire uniquement le hachage, puis insérer dans la table `admin` via phpMyAdmin l’identifiant choisi et ce hachage (jamais le mot de passe en clair). Exemple PowerShell pour ne pas afficher le mot de passe pendant sa saisie :

```powershell
$secretAdmin = Read-Host 'Mot de passe administrateur privé' -AsSecureString
$pointeurSecret = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($secretAdmin)
try {
    $env:SUPERCAR_ADMIN_PASSWORD = [Runtime.InteropServices.Marshal]::PtrToStringBSTR($pointeurSecret)
    php tools/creer-admin.php --hash
} finally {
    Remove-Item Env:SUPERCAR_ADMIN_PASSWORD -ErrorAction SilentlyContinue
    [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($pointeurSecret)
}
```

La limitation des connexions en production est de 10 échecs par adresse IP et par zone (client/admin) pendant 15 minutes. Prévoir également les protections et la supervision proposées par l’hébergeur. Aucun audit local ne remplace un test de sécurité indépendant.

## 5. Contrôles OBLIGATOIRES sur l’URL finale

- Rediriger HTTP vers HTTPS dans le panneau de l’hébergeur ; vérifier le certificat et les cookies `Secure`, `HttpOnly`, `SameSite=Lax`.
- `config.local.php`, `schema.sql`, `table.sql`, `.git/config`, `includes/db.php` et `tests/` doivent renvoyer 403 ou 404, jamais leur contenu.
- Vérifier l’accueil, les thèmes, le menu mobile, catalogue et pagination, toutes les photos et la carte de contact.
- Créer un compte fictif, se connecter, modifier le profil, demander un essai futur, puis retrouver la demande côté administration et changer son statut.
- Envoyer un message fictif et le retrouver côté administration. Les réponses sont des notes enregistrées : **aucun e-mail automatique n’est envoyé**.
- Vérifier les modifications des textes de l’accueil et du contact, puis la déconnexion. Supprimer ensuite uniquement les données de recette identifiées.
- Vérifier l’accès aux journaux privés et faire une sauvegarde/restauration de contrôle.

Les polices Google, Bootstrap et la carte Google nécessitent une connexion aux services tiers. Les polices système assurent le repli typographique. Les sources des visuels sont documentées dans le dépôt ; les droits d’exploitation commerciale et les obligations liées à une collecte réelle de données ne font pas partie de cette recette pédagogique.

## Recette locale reproductible

Le dossier `tests/` est prévu pour la ligne de commande locale et ne doit pas être publié. `php tests/prepare.php` crée une base jetable au nom `supercar_recette_...` et un administrateur aléatoire ; aucun compte du projet n’est copié. Le fichier privé `tests/.audit-state.json` contient ses identifiants.

Démarrer le serveur PHP local sur `127.0.0.1:8765`, avec `SUPERCAR_ENV=local` et `SUPERCAR_DB_NAME` défini au nom exact de cette base, puis lancer `php tests/recette.php`. La recette refuse de démarrer si l’en-tête du serveur ne correspond pas à cette base isolée. `php tests/liens.php` contrôle les liens et protections de l’Apache Wamp local, sans modification de données.

Ne jamais lancer ces outils contre le serveur hébergé ou une base contenant des données à conserver. Fermer le serveur de recette et retirer ses seuls fichiers/base jetables après vérification.
