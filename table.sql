DROP DATABASE IF EXISTS supercar;
CREATE DATABASE supercar CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE supercar;

-- TABLE MARQUE
CREATE TABLE marque (
    id_marque INT AUTO_INCREMENT PRIMARY KEY,
    nom_marque VARCHAR(100) NOT NULL UNIQUE
);

-- TABLE CLIENT
CREATE TABLE client (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    adresse VARCHAR(255),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- TABLE LOGIN CLIENT
CREATE TABLE login (
    id_login INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    id_client INT NOT NULL UNIQUE,
    FOREIGN KEY (id_client) REFERENCES client(id_client)
        ON DELETE CASCADE
);

-- TABLE ADMIN
CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL
);

-- COMPTE ADMIN DE DEPART
-- Identifiant : admin
-- Mot de passe : admin123
INSERT INTO admin (identifiant, mot_de_passe) VALUES
('admin', '$2y$10$gMnKDMQNJIA3ML1ES2Mm3ePn4s5I34Syk0Ql12QSZQPdEX.9wMW7e');

-- TABLE VOITURE
CREATE TABLE voiture (
    id_voiture INT AUTO_INCREMENT PRIMARY KEY,
    modele VARCHAR(100) NOT NULL,
    prix INT NOT NULL,
    description TEXT,
    image VARCHAR(255),
    id_marque INT NOT NULL,
    FOREIGN KEY (id_marque) REFERENCES marque(id_marque)
);

-- TABLE IMAGE VOITURE
CREATE TABLE voiture_image (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255) NOT NULL,
    id_voiture INT NOT NULL,
    FOREIGN KEY (id_voiture) REFERENCES voiture(id_voiture)
        ON DELETE CASCADE
);

-- TABLE ESSAI
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
);

-- TABLE MESSAGE CONTACT
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
);

-- TABLE SERVICES
CREATE TABLE services (
    id_services INT AUTO_INCREMENT PRIMARY KEY,
    nom_services VARCHAR(100) NOT NULL,
    description_services TEXT,
    prix_services INT
);

-- MARQUES
INSERT INTO marque (nom_marque) VALUES
('BMW'),
('Audi'),
('Mercedes');

-- VOITURES
INSERT INTO voiture (modele, prix, description, image, id_marque) VALUES
('M3', 2500000, 'Berline sportive premium avec moteur puissant et design agressif.', 'bmw1.webp', 1),
('RS6', 3200000, 'Break sportif haut de gamme combinant performance, confort et espace.', 'audi_rs6_1.jpg', 2),
('AMG GT', 3800000, 'Coupe sportif Mercedes-AMG combinant puissance, design premium et conduite dynamique.', 'mercedes1.jpg', 3),
('Serie 7', 4300000, 'Grande berline premium orientee confort, technologie et elegance.', 'bmw_serie7_1.jpg', 1),
('M8 Competition', 5600000, 'Modele BMW M tres performant avec un style coupe sportif et luxueux.', 'bmw_m8_1.jpg', 1),
('RS3', 2100000, 'Compacte sportive Audi RS, agile et puissante pour une conduite dynamique.', 'audi_rs3_1.jpg', 2),
('RS7', 4700000, 'Berline coupe sportive Audi RS alliant performances elevees et confort premium.', 'audi_rs7_1.jpg', 2),
('Classe G', 6200000, 'SUV iconique Mercedes-Benz, luxueux, robuste et tres reconnaissable.', 'mercedes_classe_g_1.jpg', 3),
('C 63 AMG', 3900000, 'Berline sportive Mercedes-AMG avec une presentation elegante et performante.', 'mercedes_c63_1.jpg', 3);

-- IMAGES BMW
INSERT INTO voiture_image (url, id_voiture) VALUES
('bmw1.webp', 1),
('bmw2.jpg', 1),
('bmw3.jpg', 1),
('bmw4.jpeg', 1),
('bmw5.jpg', 1),
('bmw6.jpg', 1);

-- IMAGES AUDI
INSERT INTO voiture_image (url, id_voiture) VALUES
('audi_rs6_1.jpg', 2),
('audi_rs6_2.jpg', 2),
('audi_rs6_3.jpg', 2);

-- IMAGES MERCEDES
INSERT INTO voiture_image (url, id_voiture) VALUES
('mercedes1.jpg', 3),
('mercedes2.jpeg', 3),
('mercedes3.jpg', 3),
('mercedes4.jpg', 3),
('mercedes5.jpg', 3),
('mercedes6.jpeg', 3);

-- IMAGES BMW SERIE 7
INSERT INTO voiture_image (url, id_voiture) VALUES
('bmw_serie7_1.jpg', 4),
('bmw_serie7_2.jpg', 4),
('bmw_serie7_3.jpg', 4);

-- IMAGES BMW M8 COMPETITION
INSERT INTO voiture_image (url, id_voiture) VALUES
('bmw_m8_1.jpg', 5),
('bmw_m8_2.jpg', 5),
('bmw_m8_3.jpg', 5);

-- IMAGES AUDI RS3
INSERT INTO voiture_image (url, id_voiture) VALUES
('audi_rs3_1.jpg', 6),
('audi_rs3_2.jpg', 6),
('audi_rs3_3.jpg', 6);

-- IMAGES AUDI RS7
INSERT INTO voiture_image (url, id_voiture) VALUES
('audi_rs7_1.jpg', 7),
('audi_rs7_2.jpg', 7),
('audi_rs7_3.jpg', 7);

-- IMAGES MERCEDES CLASSE G
INSERT INTO voiture_image (url, id_voiture) VALUES
('mercedes_classe_g_1.jpg', 8),
('mercedes_classe_g_2.jpg', 8),
('mercedes_classe_g_3.jpg', 8);

-- IMAGES MERCEDES C 63 AMG
INSERT INTO voiture_image (url, id_voiture) VALUES
('mercedes_c63_1.jpg', 9),
('mercedes_c63_2.jpg', 9),
('mercedes_c63_3.jpg', 9);

-- SERVICES
INSERT INTO services (nom_services, description_services, prix_services) VALUES
('Essai personnalise', 'Reservation d''un essai avec accompagnement par un conseiller SuperCar.', 0),
('Conseil achat', 'Accompagnement du client dans le choix du vehicule adapte a ses besoins.', 0),
('Livraison a domicile', 'Livraison du vehicule au domicile du client apres validation de l''achat.', 5000);

DELIMITER //

-- PROCEDURE STOCKEE
CREATE PROCEDURE sp_liste_essais()
BEGIN
    SELECT
        e.id_essai,
        e.date_essai,
        e.heure_essai,
        e.statut,
        e.date_demande,
        c.nom,
        c.email,
        c.telephone,
        c.adresse,
        v.modele,
        m.nom_marque
    FROM essai e
    INNER JOIN client c ON e.id_client = c.id_client
    INNER JOIN voiture v ON e.id_voiture = v.id_voiture
    INNER JOIN marque m ON v.id_marque = m.id_marque
    ORDER BY e.date_demande DESC;
END//

-- TRIGGER
CREATE TRIGGER trg_essai_statut_default
BEFORE INSERT ON essai
FOR EACH ROW
BEGIN
    IF NEW.statut IS NULL OR NEW.statut = '' THEN
        SET NEW.statut = 'en attente';
    END IF;
END//

DELIMITER ;
