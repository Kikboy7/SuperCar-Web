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
    annee INT,
    carburant VARCHAR(50),
    boite VARCHAR(50),
    puissance INT,
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
('Mercedes'),
('Porsche'),
('Ferrari'),
('Jeep'),
('Land Rover');

-- VOITURES
INSERT INTO voiture (id_voiture, modele, prix, description, image, annee, carburant, boite, puissance, id_marque) VALUES
(1, 'M3 Competition', 3200000, 'La BMW M3 Competition incarne la berline sportive par excellence : un moteur six cylindres biturbo, une transmission integrale M xDrive et un chassis regle sur mesure pour une conduite aussi precise qu''exaltante. Design agressif, habitacle premium et technologies de pointe la placent au sommet de sa categorie.', 'bmw_m3_1.jpg', 2024, 'Essence', 'Automatique', 510, 1),
(5, 'M8 Competition', 6800000, 'Le summum du grand tourisme sportif allemand. La BMW M8 Competition associe un V8 biturbo de 625 ch a un coup d''essence elegant et une technologie de pointe. Un habitacle luxueux en cuir Merino et une acceleration de 0 a 100 km/h en 3,2 secondes.', 'bmw_m8_1.jpg', 2023, 'Essence', 'Automatique', 625, 1),
(4, 'Serie 7', 5200000, 'La grande berline de luxe signee BMW. La Serie 7 offre un confort absolu avec ses sieges massants, son ecran cinema arriere et un systeme audio Bowers & Wilkins. Sa motorisation hybride rechargeable allie elegance, silence et respect de l''environnement.', 'bmw_serie7_1.jpg', 2024, 'Hybride', 'Automatique', 380, 1),
(2, 'RS6 Avant', 3900000, 'Le break sportif de reference. L''Audi RS6 Avant combine un V8 biturbo de 600 ch a un habitacle spacieux et raffiné. Son châssis adaptatif et sa transmission quattro assurent une tenue de route exemplaire, meme sur les longs trajets.', 'audi_rs6_1.jpg', 2024, 'Essence', 'Automatique', 600, 2),
(6, 'RS3 Sportback', 2500000, 'Compacte la plus performante de sa categorie. L''Audi RS3 Sportback enfile un cinq cylindres de 400 ch a caractere, un son unique et une agilite redoutable. Parfaite pour la ville comme pour les routes sinueuses.', 'audi_rs3_1.jpg', 2024, 'Essence', 'Automatique', 400, 2),
(7, 'RS7 Sportback', 5500000, 'Berline cinq portes au style coupé. L''Audi RS7 Sportback marie elegances et performance avec son V8 de 600 ch, sa ligne fluide et ses technologies d''aide a la conduite dernier cri.', 'audi_rs7_1.jpg', 2023, 'Essence', 'Automatique', 600, 2),
(3, 'AMG GT', 4600000, 'La sportive deux places signee Mercedes-AMG. L''AMG GT offre un V8 biturbo de 585 ch, un châssis réglable et un design racé qui ne laisse personne indifférent. Un concentré d''adrénaline et de luxe.', 'mercedes_amg_gt_1.jpg', 2023, 'Essence', 'Automatique', 585, 3),
(8, 'Classe G', 7800000, 'L''icone absolue du tout-terrain de luxe. La Classe G conserve sa silhouette carree legendaire tout en offrant un confort exceptionnel. Du jamais vu : trois blocs de verrouillage de différentiels pour dompter les terrains les plus extremes.', 'mercedes_classe_g_1.jpg', 2024, 'Diesel', 'Automatique', 421, 3),
(9, 'C 63 AMG', 4700000, 'Moteur electronique et thermique reunis : la C 63 AMG affiche 680 ch pour une berline au style compact. Trailonne et technologique, elle offre une experience de conduite hybride haute performance.', 'mercedes_c63_1.jpg', 2024, 'Hybride', 'Automatique', 680, 3),
(10, '911 Turbo S', 9200000, 'La reference des sportives. La Porsche 911 Turbo S conjugue un flat-six biturbo de 650 ch, une adhérence absolue et un raffinement incomparable. 0 a 100 km/h en 2,7 secondes, pour un plaisir de conduite sans égal.', 'porsche_911_1.jpg', 2024, 'Essence', 'Automatique', 650, 4),
(11, 'Cayenne Turbo GT', 7800000, 'Le SUV le plus sportif du monde. Le Cayenne Turbo GT associe 660 ch, un châssis abaissé et un dynamisme de supercar. Le confort d''un grand SUV et le tempérament d''une sportive.', 'porsche_cayenne_1.jpg', 2024, 'Essence', 'Automatique', 660, 4),
(12, 'Taycan Turbo S', 9800000, 'L''electrique n''a jamais ete si sportive. La Taycan Turbo S développe jusqu''a 761 ch grâce a son groupe motopropulseur à deux moteurs. Recharge rapide, silence absolu et acceleration fulgurante.', 'porsche_taycan_1.jpg', 2024, 'Electrique', 'Automatique', 761, 4),
(13, 'F8 Tributo', 14500000, 'L''hommage au V8 le plus victorieux de l''histoire. La F8 Tributo enchaîne 720 ch et une aerodynamique parfaite. Un chef-d''œuvre italien, a la fois sculptural et ultra performant.', 'ferrari_f8_1.jpg', 2023, 'Essence', 'Automatique', 720, 5),
(14, 'Roma', 12000000, 'Dolce vita. La Ferrari Roma capture l''esprit des grands tourismes des annees 60 : lignes elegantes, habitacle somptueux et moteur V8 de 620 ch. L''alliance parfaite du style et de la performance.', 'ferrari_roma_1.jpg', 2024, 'Essence', 'Automatique', 620, 5),
(15, 'Purosangue', 18500000, 'Le premier SUV de l''histoire de Ferrari. Le Purosangue est propulse par le celebre V12 atmosphérique de 725 ch. Performance absolue, elegance italienne et confort de voyage.', 'ferrari_purosangue_1.jpg', 2024, 'Essence', 'Automatique', 725, 5),
(16, 'Wrangler Rubicon', 3300000, 'Le mythe americain tout-terrain. Le Wrangler Rubicon est equipe d''essieux renforces, de ponts avant rigide et d''une transmission 4x4 digne des plus gros franchisseurs.', 'jeep_wrangler_1.jpg', 2024, 'Essence', 'Manuelle', 270, 6),
(17, 'Grand Cherokee', 4200000, 'Luxe, confort et capacites tout-terrain. Le Grand Cherokee offre un habitacle premium, des technologies de pointe et une motorisation hybride efficace pour les longs voyages.', 'jeep_grand_cherokee_1.jpg', 2024, 'Hybride', 'Automatique', 375, 6),
(18, 'Compass', 2200000, 'Compacte et tout-terrain. Le Jeep Compass conjugue agilite urbaine et caracteristiques 4x4, avec un design musclé et un habitacle connecte.', 'jeep_compass_1.jpg', 2024, 'Essence', 'Automatique', 180, 6),
(19, 'Range Rover Sport', 7800000, 'Le SUV britannique par excellence. Le Range Rover Sport marie raffinement, technologies dernier cri et capacites off-road inégalées. Une silhouette epuree et une présence majestueuse.', 'landrover_range_rover_1.jpg', 2024, 'Hybride', 'Automatique', 530, 7),
(20, 'Defender 110', 5200000, 'Nouvelle generation d''une legende. Le Defender 110 conserve l''esprit baroudeur de son aieul tout en offrant un confort moderne et des capacites tout-terrain exceptionnelles.', 'landrover_defender_1.jpg', 2023, 'Diesel', 'Automatique', 300, 7),
(21, 'Evoque', 3800000, 'Le SUV compact premium. L''Evoque affiche un design urbain soigne, un habitacle raffiné et une conduite douce. L''entree dans l''univers Range Rover.', 'landrover_evoque_1.jpg', 2024, 'Hybride', 'Automatique', 200, 7);

-- IMAGES VOITURES (galerie)
-- Bmw M3 Competition (id 1)
INSERT INTO voiture_image (url, id_voiture) VALUES
('bmw_m3_1.jpg', 1),
('bmw_m3_2.jpg', 1),
('bmw_m3_3.jpg', 1),
('bmw_m3_4.jpg', 1),
('bmw_m3_5.jpg', 1),
('bmw_m3_6.jpg', 1);
-- Bmw M8 Competition (id 5)
INSERT INTO voiture_image (url, id_voiture) VALUES
('bmw_m8_1.jpg', 5),
('bmw_m8_2.jpg', 5),
('bmw_m8_3.jpg', 5),
('bmw_m8_4.jpg', 5),
('bmw_m8_5.jpg', 5),
('bmw_m8_6.jpg', 5);
-- Bmw Serie 7 (id 4)
INSERT INTO voiture_image (url, id_voiture) VALUES
('bmw_serie7_1.jpg', 4),
('bmw_serie7_2.jpg', 4),
('bmw_serie7_3.jpg', 4),
('bmw_serie7_4.jpg', 4),
('bmw_serie7_5.jpg', 4),
('bmw_serie7_6.jpg', 4);
-- Audi RS6 Avant (id 2)
INSERT INTO voiture_image (url, id_voiture) VALUES
('audi_rs6_1.jpg', 2),
('audi_rs6_2.jpg', 2),
('audi_rs6_3.jpg', 2),
('audi_rs6_4.jpg', 2),
('audi_rs6_5.jpg', 2),
('audi_rs6_6.jpg', 2);
-- Audi RS3 Sportback (id 6)
INSERT INTO voiture_image (url, id_voiture) VALUES
('audi_rs3_1.jpg', 6),
('audi_rs3_2.jpg', 6),
('audi_rs3_3.jpg', 6),
('audi_rs3_4.jpg', 6),
('audi_rs3_5.jpg', 6),
('audi_rs3_6.jpg', 6);
-- Audi RS7 Sportback (id 7)
INSERT INTO voiture_image (url, id_voiture) VALUES
('audi_rs7_1.jpg', 7),
('audi_rs7_2.jpg', 7),
('audi_rs7_3.jpg', 7),
('audi_rs7_4.jpg', 7),
('audi_rs7_5.jpg', 7),
('audi_rs7_6.jpg', 7);
-- Mercedes AMG GT (id 3)
INSERT INTO voiture_image (url, id_voiture) VALUES
('mercedes_amg_gt_1.jpg', 3),
('mercedes_amg_gt_2.jpg', 3),
('mercedes_amg_gt_3.jpg', 3),
('mercedes_amg_gt_4.jpg', 3),
('mercedes_amg_gt_5.jpg', 3),
('mercedes_amg_gt_6.jpg', 3);
-- Mercedes Classe G (id 8)
INSERT INTO voiture_image (url, id_voiture) VALUES
('mercedes_classe_g_1.jpg', 8),
('mercedes_classe_g_2.jpg', 8),
('mercedes_classe_g_3.jpg', 8),
('mercedes_classe_g_4.jpg', 8),
('mercedes_classe_g_5.jpg', 8),
('mercedes_classe_g_6.jpg', 8);
-- Mercedes C 63 AMG (id 9)
INSERT INTO voiture_image (url, id_voiture) VALUES
('mercedes_c63_1.jpg', 9),
('mercedes_c63_2.jpg', 9),
('mercedes_c63_3.jpg', 9),
('mercedes_c63_4.jpg', 9),
('mercedes_c63_5.jpg', 9),
('mercedes_c63_6.jpg', 9);
-- Porsche 911 Turbo S (id 10)
INSERT INTO voiture_image (url, id_voiture) VALUES
('porsche_911_1.jpg', 10),
('porsche_911_2.jpg', 10),
('porsche_911_3.jpg', 10),
('porsche_911_4.jpg', 10),
('porsche_911_5.jpg', 10),
('porsche_911_6.jpg', 10);
-- Porsche Cayenne Turbo GT (id 11)
INSERT INTO voiture_image (url, id_voiture) VALUES
('porsche_cayenne_1.jpg', 11),
('porsche_cayenne_2.jpg', 11),
('porsche_cayenne_3.jpg', 11),
('porsche_cayenne_4.jpg', 11),
('porsche_cayenne_5.jpg', 11),
('porsche_cayenne_6.jpg', 11);
-- Porsche Taycan Turbo S (id 12)
INSERT INTO voiture_image (url, id_voiture) VALUES
('porsche_taycan_1.jpg', 12),
('porsche_taycan_2.jpg', 12),
('porsche_taycan_3.jpg', 12),
('porsche_taycan_4.jpg', 12),
('porsche_taycan_5.jpg', 12),
('porsche_taycan_6.jpg', 12);
-- Ferrari F8 Tributo (id 13)
INSERT INTO voiture_image (url, id_voiture) VALUES
('ferrari_f8_1.jpg', 13),
('ferrari_f8_2.jpg', 13),
('ferrari_f8_3.jpg', 13),
('ferrari_f8_4.jpg', 13),
('ferrari_f8_5.jpg', 13),
('ferrari_f8_6.jpg', 13);
-- Ferrari Roma (id 14)
INSERT INTO voiture_image (url, id_voiture) VALUES
('ferrari_roma_1.jpg', 14),
('ferrari_roma_2.jpg', 14),
('ferrari_roma_3.jpg', 14),
('ferrari_roma_4.jpg', 14),
('ferrari_roma_5.jpg', 14),
('ferrari_roma_6.jpg', 14);
-- Ferrari Purosangue (id 15)
INSERT INTO voiture_image (url, id_voiture) VALUES
('ferrari_purosangue_1.jpg', 15),
('ferrari_purosangue_2.jpg', 15),
('ferrari_purosangue_3.jpg', 15),
('ferrari_purosangue_4.jpg', 15),
('ferrari_purosangue_5.jpg', 15),
('ferrari_purosangue_6.jpg', 15);
-- Jeep Wrangler Rubicon (id 16)
INSERT INTO voiture_image (url, id_voiture) VALUES
('jeep_wrangler_1.jpg', 16),
('jeep_wrangler_2.jpg', 16),
('jeep_wrangler_3.jpg', 16),
('jeep_wrangler_4.jpg', 16),
('jeep_wrangler_5.jpg', 16),
('jeep_wrangler_6.jpg', 16);
-- Jeep Grand Cherokee (id 17)
INSERT INTO voiture_image (url, id_voiture) VALUES
('jeep_grand_cherokee_1.jpg', 17),
('jeep_grand_cherokee_2.jpg', 17),
('jeep_grand_cherokee_3.jpg', 17),
('jeep_grand_cherokee_4.jpg', 17),
('jeep_grand_cherokee_5.jpg', 17),
('jeep_grand_cherokee_6.jpg', 17);
-- Jeep Compass (id 18)
INSERT INTO voiture_image (url, id_voiture) VALUES
('jeep_compass_1.jpg', 18),
('jeep_compass_2.jpg', 18),
('jeep_compass_3.jpg', 18),
('jeep_compass_4.jpg', 18),
('jeep_compass_5.jpg', 18),
('jeep_compass_6.jpg', 18);
-- Landrover Range Rover Sport (id 19)
INSERT INTO voiture_image (url, id_voiture) VALUES
('landrover_range_rover_1.jpg', 19),
('landrover_range_rover_2.jpg', 19),
('landrover_range_rover_3.jpg', 19),
('landrover_range_rover_4.jpg', 19),
('landrover_range_rover_5.jpg', 19),
('landrover_range_rover_6.jpg', 19);
-- Landrover Defender 110 (id 20)
INSERT INTO voiture_image (url, id_voiture) VALUES
('landrover_defender_1.jpg', 20),
('landrover_defender_2.jpg', 20),
('landrover_defender_3.jpg', 20),
('landrover_defender_4.jpg', 20),
('landrover_defender_5.jpg', 20),
('landrover_defender_6.jpg', 20);
-- Landrover Evoque (id 21)
INSERT INTO voiture_image (url, id_voiture) VALUES
('landrover_evoque_1.jpg', 21),
('landrover_evoque_2.jpg', 21),
('landrover_evoque_3.jpg', 21),
('landrover_evoque_4.jpg', 21),
('landrover_evoque_5.jpg', 21),
('landrover_evoque_6.jpg', 21);

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