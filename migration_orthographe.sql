-- Corrections éditoriales ciblées : conserve les autres textes personnalisés.
SET NAMES utf8mb4;
USE supercar;

UPDATE services
SET details_services = REPLACE(details_services, 'Chez SuperCar, aucun achat ne se décide sans volant.', 'Un essai est une étape essentielle avant de choisir votre voiture.')
WHERE details_services LIKE '%Chez SuperCar, aucun achat ne se décide sans volant.%';

UPDATE services
SET details_services = REPLACE(details_services, 'les clés en main propre', 'les clés en mains propres')
WHERE details_services LIKE '%les clés en main propre%';
