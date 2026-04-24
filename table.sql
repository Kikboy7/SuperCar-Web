CREATE DATABASE IF NOT EXISTS supercar;
USE supercar;

-- TABLE MARQUE
CREATE TABLE marque (
    id_marque INT AUTO_INCREMENT PRIMARY KEY,
    nom_marque VARCHAR(100) NOT NULL
);

-- TABLE CLIENT
CREATE TABLE client (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telephone VARCHAR(20),
    adresse VARCHAR(255)
);

-- TABLE LOGIN (lié au client)
CREATE TABLE login (
    id_login INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(100) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    id_client INT,
    FOREIGN KEY (id_client) REFERENCES client(id_client)
);

-- TABLE VOITURE
CREATE TABLE voiture (
    id_voiture INT AUTO_INCREMENT PRIMARY KEY,
    modele VARCHAR(100) NOT NULL,
    prix INT NOT NULL,
    description VARCHAR(255),
    image VARCHAR(255),
    id_marque INT,
    FOREIGN KEY (id_marque) REFERENCES marque(id_marque)
);

-- TABLE ESSAI
CREATE TABLE essai (
    id_essai INT AUTO_INCREMENT PRIMARY KEY,
    date_essai DATE NOT NULL,
    statut VARCHAR(50),
    id_client INT,
    id_voiture INT,
    FOREIGN KEY (id_client) REFERENCES client(id_client),
    FOREIGN KEY (id_voiture) REFERENCES voiture(id_voiture)
);

-- TABLE MESSAGE
CREATE TABLE message (
    id_message INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message VARCHAR(255) NOT NULL,
    date_message DATE
);

-- TABLE SERVICES
CREATE TABLE services (
    id_services INT AUTO_INCREMENT PRIMARY KEY,
    nom_services VARCHAR(100),
    description_services VARCHAR(255),
    prix_services INT
);

-- Marques
INSERT INTO marque (nom_marque) VALUES
('BMW'),
('Audi'),
('Mercedes');

-- Voitures
INSERT INTO voiture (modele, prix, image, id_marque) VALUES
('M3', 2500000, 'bmw1.webp', 1),
('RS6', 3200000, 'audi1.jpg', 2),
('AMG', 3800000, 'mercedes1.jpg', 3);