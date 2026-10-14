USE supercar;

-- Cette migration ajoute les visuels, les presentations, les points forts
-- et les 4 services supplementaires, sans supprimer les donnees deja
-- presentes dans la base.
-- A executer une seule fois (les installations neuves utilisent table.sql).
ALTER TABLE services
    ADD COLUMN image_services VARCHAR(255) NULL AFTER prix_services,
    ADD COLUMN details_services TEXT NULL AFTER description_services,
    ADD COLUMN points_services TEXT NULL AFTER details_services;

UPDATE services SET image_services = 'service_essai.jpg'
WHERE nom_services = 'Essai personnalisé';

UPDATE services SET image_services = 'service_conseil.jpg'
WHERE nom_services = 'Conseil d''achat';

UPDATE services SET image_services = 'service_livraison.jpg'
WHERE nom_services = 'Livraison à domicile';

UPDATE services SET image_services = 'service_demarche.jpg'
WHERE nom_services = 'Démarches administratives';

UPDATE services
SET details_services = 'Un essai est une étape essentielle avant de choisir votre voiture. L''essai personnalisé vous permet de prendre en main le véhicule de votre choix sur un parcours adapté, en ville comme sur voie rapide.'
WHERE nom_services = 'Essai personnalisé';

UPDATE services
SET details_services = 'Acheter une voiture premium est un investissement : notre rôle est de vous aider à faire le bon choix, pas de vous vendre à tout prix. Nous analysons avec vous votre budget, votre usage et vos préférences, puis nous comparons ensemble les modèles du catalogue.'
WHERE nom_services = 'Conseil d''achat';

UPDATE services
SET details_services = 'Votre voiture vous est remise où vous voulez, dans un état irréprochable : véhicule entièrement préparé, clés remises en main propre avec tous les documents, et explications pour la prise en main.'
WHERE nom_services = 'Livraison à domicile';

UPDATE services
SET details_services = 'L''immatriculation et l''assurance prennent du temps : SuperCar s''en occupe pour vous. Nous constituons le dossier, effectuons les démarches et vous tenons informé jusqu''à la remise des documents.'
WHERE nom_services = 'Démarches administratives';

UPDATE services SET points_services = 'Essai gratuit et sans engagement
Parcours ville et voie rapide
Conseiller dédié pendant l''essai'
WHERE nom_services = 'Essai personnalisé';

UPDATE services SET points_services = 'Analyse de vos besoins et de votre budget
Comparatif transparent des modèles
Avis clair et chiffré'
WHERE nom_services = 'Conseil d''achat';

UPDATE services SET points_services = 'Véhicule préparé et contrôlé
Remise des clés en main propre
Explications pour la prise en main'
WHERE nom_services = 'Livraison à domicile';

UPDATE services SET points_services = 'Dossier constitué avec vous
Immatriculation effectuée
Assurance adaptée proposée'
WHERE nom_services = 'Démarches administratives';

-- Les 4 services supplementaires (inseres seulement s''ils n''existent pas).
INSERT INTO services (nom_services, description_services, details_services, points_services, prix_services, image_services)
SELECT 'Reprise de votre ancien véhicule', 'Estimation offerte de votre véhicule actuel, déduite directement du prix de votre nouvelle voiture.', 'Pas besoin de vendre votre voiture vous-même : SuperCar reprend votre véhicule actuel au juste prix, après inspection avec vous.', 'Inspection gratuite de 30 minutes
Offre écrite sous 24 heures
Montant déduit du prix d''achat', 0, 'service_reprise.jpg'
WHERE NOT EXISTS (SELECT 1 FROM services WHERE nom_services = 'Reprise de votre ancien véhicule');

INSERT INTO services (nom_services, description_services, details_services, points_services, prix_services, image_services)
SELECT 'Financement sur mesure', 'Simulation claire avec nos partenaires : crédit classique ou LOA, sans surprise.', 'Nous étudions avec vous la formule adaptée (apport, durée, mensualités), en crédit classique comme en location avec option d''achat, avec une réponse de principe rapide.', 'Simulation gratuite en 15 minutes
Crédit ou LOA au choix
Réponse de principe rapide', 0, 'service_financement.jpg'
WHERE NOT EXISTS (SELECT 1 FROM services WHERE nom_services = 'Financement sur mesure');

INSERT INTO services (nom_services, description_services, details_services, points_services, prix_services, image_services)
SELECT 'Garantie 12 mois', 'Roulez serein : moteur, boîte de vitesses et transmission couverts pendant un an.', 'La garantie 12 mois prend le relais après la livraison : moteur, boîte de vitesses et transmission couverts, pièces et main-d''œuvre incluses, dans notre atelier.', 'Moteur, boîte et transmission couverts
Pièces et main-d''œuvre incluses
Valable 12 mois après la livraison', 15000, 'service_garantie.jpg'
WHERE NOT EXISTS (SELECT 1 FROM services WHERE nom_services = 'Garantie 12 mois');

INSERT INTO services (nom_services, description_services, details_services, points_services, prix_services, image_services)
SELECT 'Detailing & entretien', 'Nettoyage premium, polissage et contrôle 50 points par nos techniciens.', 'Nos techniciens bichonnent votre voiture : nettoyage complet intérieur et extérieur, polissage de la carrosserie et contrôle 50 points pour rouler en toute confiance.', 'Nettoyage intérieur et extérieur complet
Polissage et lustrage carrosserie
Contrôle 50 points inclus', 4500, 'service_detailing.jpg'
WHERE NOT EXISTS (SELECT 1 FROM services WHERE nom_services = 'Detailing & entretien');
