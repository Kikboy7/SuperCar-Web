-- Ajout des interlocuteurs sans modifier les coordonnées personnalisées.
SET NAMES utf8mb4;
USE supercar;
INSERT IGNORE INTO contenu_contact (cle, valeur) VALUES
('contact_1_nom', 'Camille Laurent'),
('contact_1_role', 'Conseillère clientèle'),
('contact_2_nom', 'Julien Morel'),
('contact_2_role', 'Immatriculation'),
('contacts_note', 'Interlocuteurs fictifs pour ce projet pédagogique.');

UPDATE contenu_contact
SET valeur = 'Personnes à contacter'
WHERE cle = 'telephone_label' AND valeur = 'Appelez-nous';

-- Les anciens champs visite_note et visite_bouton restent stockés,
-- mais ne sont plus affichés ni proposés dans l’administration.
