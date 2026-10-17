-- Schéma pour une NOUVELLE base vide (PHP 8.2+ / MySQL 8+ ou MariaDB 10.6+).
-- Aucune suppression, aucun compte prédéfini, aucun CREATE DATABASE ni USE.
-- Sélectionner la base cible avant import. Importer ensuite contenu_site.sql.
SET NAMES utf8mb4;

CREATE TABLE marque (
    id_marque INT AUTO_INCREMENT PRIMARY KEY,
    nom_marque VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE client (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    adresse VARCHAR(255),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE login (
    id_login INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    id_client INT NOT NULL UNIQUE,
    FOREIGN KEY (id_client) REFERENCES client(id_client)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE voiture (
    id_voiture INT AUTO_INCREMENT PRIMARY KEY,
    modele VARCHAR(100) NOT NULL,
    prix INT NOT NULL,
    description TEXT,
    image VARCHAR(255),
    annee INT,
    carburant VARCHAR(50),
    boite VARCHAR(50),
    puissance INT,
    id_marque INT NOT NULL,
    FOREIGN KEY (id_marque) REFERENCES marque(id_marque)
) ENGINE=InnoDB;

CREATE TABLE voiture_image (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255) NOT NULL,
    id_voiture INT NOT NULL,
    FOREIGN KEY (id_voiture) REFERENCES voiture(id_voiture)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE essai (
    id_essai INT AUTO_INCREMENT PRIMARY KEY,
    date_essai DATE NOT NULL,
    heure_essai TIME NOT NULL,
    statut ENUM('en attente', 'valide', 'refuse') DEFAULT 'en attente',
    id_client INT NOT NULL,
    id_voiture INT NOT NULL,
    date_demande DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES client(id_client),
    FOREIGN KEY (id_voiture) REFERENCES voiture(id_voiture)
) ENGINE=InnoDB;

CREATE TABLE message (
    id_message INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    date_message DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_client INT NULL,
    statut_message ENUM('nouveau', 'en cours', 'traite') DEFAULT 'nouveau',
    reponse_admin TEXT,
    date_reponse DATETIME,
    FOREIGN KEY (id_client) REFERENCES client(id_client)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE services (
    id_services INT AUTO_INCREMENT PRIMARY KEY,
    nom_services VARCHAR(100) NOT NULL,
    description_services TEXT,
    details_services TEXT,
    points_services TEXT,
    prix_services INT,
    image_services VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE contenu_accueil (
    id_contenu TINYINT PRIMARY KEY,
    hero_surtitre VARCHAR(150) NOT NULL,
    hero_titre_ligne1 VARCHAR(150) NOT NULL,
    hero_titre_ligne2 VARCHAR(150) NOT NULL,
    hero_description TEXT NOT NULL,
    cta_titre VARCHAR(150) NOT NULL,
    cta_description TEXT NOT NULL,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contenu_contact (
    cle VARCHAR(64) PRIMARY KEY,
    valeur TEXT NOT NULL,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
