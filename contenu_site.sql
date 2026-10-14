-- Instantané du contenu PUBLIC de SuperCar au 14 octobre 2026.
-- Aucun compte client/admin, mot de passe, message ou demande d’essai.
-- Nouvelle installation : importer table.sql, puis ce fichier.
-- Installation existante : appliquer les migrations nécessaires avant ce fichier.
-- Remplace les valeurs des contenus correspondants ; sauvegardez vos contenus locaux avant import.
SET NAMES utf8mb4;
USE supercar;
START TRANSACTION;

-- marque : 7 lignes.
INSERT INTO `marque` (`id_marque`, `nom_marque`) VALUES
(1, 'BMW'),
(2, 'Audi'),
(3, 'Mercedes'),
(4, 'Porsche'),
(5, 'Ferrari'),
(6, 'Jeep'),
(7, 'Land Rover')
ON DUPLICATE KEY UPDATE `nom_marque` = VALUES(`nom_marque`);

-- voiture : 21 lignes.
INSERT INTO `voiture` (`id_voiture`, `modele`, `prix`, `description`, `image`, `id_marque`, `annee`, `carburant`, `boite`, `puissance`) VALUES
(1, 'M3 Competition', 3200000, 'La BMW M3 Competition incarne la berline sportive par excellence : un moteur six cylindres biturbo, une transmission intégrale M xDrive et un châssis réglé sur mesure pour une conduite aussi précise qu''exaltante. Son design agressif, son habitacle premium et ses technologies de pointe la placent au sommet de sa catégorie.', 'bmw_m3_1.jpg', 1, 2024, 'Essence', 'Automatique', 510),
(2, 'RS6 Avant', 3900000, 'Le break sportif de référence. L''Audi RS6 Avant combine un V8 biturbo de 600 ch à un habitacle spacieux et raffiné. Son châssis adaptatif et sa transmission quattro assurent une tenue de route exemplaire, même sur les longs trajets.', 'audi_rs6_1.jpg', 2, 2024, 'Essence', 'Automatique', 600),
(3, 'AMG GT', 4600000, 'La sportive deux places signée Mercedes-AMG. L''AMG GT offre un V8 biturbo de 585 ch, un châssis réglable et un design racé qui ne laisse personne indifférent. Un concentré d''adrénaline et de luxe.', 'mercedes_amg_gt_1.jpg', 3, 2023, 'Essence', 'Automatique', 585),
(4, 'Série 7', 5200000, 'La grande berline de luxe signée BMW. La Série 7 offre un confort absolu avec ses sièges massants, son écran cinéma arrière et son système audio Bowers & Wilkins. Sa motorisation hybride rechargeable allie élégance, silence et respect de l''environnement.', 'bmw_serie7_1.jpg', 1, 2024, 'Hybride', 'Automatique', 380),
(5, 'M8 Competition', 6800000, 'Le summum du grand tourisme sportif allemand. La BMW M8 Competition associe un V8 biturbo de 625 ch à un coupé élégant et à une technologie de pointe. Son habitacle luxueux en cuir Merino accompagne une accélération de 0 à 100 km/h en 3,2 secondes.', 'bmw_m8_1.jpg', 1, 2023, 'Essence', 'Automatique', 625),
(6, 'RS3 Sportback', 2500000, 'Compacte parmi les plus performantes de sa catégorie, l''Audi RS3 Sportback embarque un moteur cinq cylindres de 400 ch au caractère affirmé, une sonorité unique et une agilité redoutable. Elle est parfaite pour la ville comme pour les routes sinueuses.', 'audi_rs3_1.jpg', 2, 2024, 'Essence', 'Automatique', 400),
(7, 'RS7 Sportback', 5500000, 'Cette berline cinq portes au style coupé marie élégance et performance. L''Audi RS7 Sportback séduit par son V8 de 600 ch, sa ligne fluide et ses technologies d''aide à la conduite de dernière génération.', 'audi_rs7_1.jpg', 2, 2023, 'Essence', 'Automatique', 600),
(8, 'Classe G', 7800000, 'L''icône absolue du tout-terrain de luxe. La Classe G conserve sa silhouette carrée légendaire tout en offrant un confort exceptionnel. Ses trois blocages de différentiel lui permettent de dompter les terrains les plus extrêmes.', 'mercedes_classe_g_1.jpg', 3, 2024, 'Diesel', 'Automatique', 421),
(9, 'C 63 AMG', 4700000, 'Motorisations électrique et thermique réunies : la C 63 AMG affiche 680 ch dans une berline au style compact. Racée et technologique, elle offre une expérience de conduite hybride à hautes performances.', 'mercedes_c63_1.jpg', 3, 2024, 'Hybride', 'Automatique', 680),
(10, '911 Turbo S', 9200000, 'La référence des sportives. La Porsche 911 Turbo S conjugue un flat-six biturbo de 650 ch, une adhérence absolue et un raffinement incomparable. Elle passe de 0 à 100 km/h en 2,7 secondes, pour un plaisir de conduite sans égal.', 'porsche_911_1.jpg', 4, 2024, 'Essence', 'Automatique', 650),
(11, 'Cayenne Turbo GT', 7800000, 'Le SUV le plus sportif du monde. Le Cayenne Turbo GT associe 660 ch, un châssis abaissé et un dynamisme de supercar. Le confort d''un grand SUV et le tempérament d''une sportive.', 'porsche_cayenne_1.jpg', 4, 2024, 'Essence', 'Automatique', 660),
(12, 'Taycan Turbo S', 9800000, 'L''électrique n''a jamais été aussi sportive. La Taycan Turbo S développe jusqu''à 761 ch grâce à son groupe motopropulseur à deux moteurs. Recharge rapide, silence absolu et accélération fulgurante.', 'porsche_taycan_1.jpg', 4, 2024, 'Électrique', 'Automatique', 761),
(13, 'F8 Tributo', 14500000, 'L''hommage au V8 le plus victorieux de l''histoire. La F8 Tributo associe 720 ch à une aérodynamique parfaitement maîtrisée. Un chef-d''œuvre italien, à la fois sculptural et ultraperformant.', 'ferrari_f8_1.jpg', 5, 2023, 'Essence', 'Automatique', 720),
(14, 'Roma', 12000000, 'La dolce vita. La Ferrari Roma capture l''esprit des voitures de grand tourisme des années 1960 : lignes élégantes, habitacle somptueux et moteur V8 de 620 ch. L''alliance parfaite du style et de la performance.', 'ferrari_roma_1.jpg', 5, 2024, 'Essence', 'Automatique', 620),
(15, 'Purosangue', 18500000, 'Le premier SUV de l''histoire de Ferrari. Le Purosangue est propulsé par le célèbre V12 atmosphérique de 725 ch. Performance absolue, élégance italienne et confort de voyage.', 'ferrari_purosangue_1.jpg', 5, 2024, 'Essence', 'Automatique', 725),
(16, 'Wrangler Rubicon', 3300000, 'Le mythe américain du tout-terrain. Le Wrangler Rubicon est équipé d''essieux renforcés, de ponts rigides et d''une transmission 4x4 conçue pour les franchissements les plus difficiles.', 'jeep_wrangler_1.jpg', 6, 2024, 'Essence', 'Manuelle', 270),
(17, 'Grand Cherokee', 4200000, 'Luxe, confort et capacités tout-terrain. Le Grand Cherokee offre un habitacle premium, des technologies de pointe et une motorisation hybride efficace pour les longs voyages.', 'jeep_grand_cherokee_1.jpg', 6, 2024, 'Hybride', 'Automatique', 375),
(18, 'Compass', 2200000, 'Compact et polyvalent, le Jeep Compass conjugue agilité urbaine et capacités tout-terrain, avec un design musclé et un habitacle connecté.', 'jeep_compass_1.jpg', 6, 2024, 'Essence', 'Automatique', 180),
(19, 'Range Rover Sport', 7800000, 'Le SUV britannique par excellence. Le Range Rover Sport marie raffinement, technologies de dernière génération et capacités tout-terrain remarquables. Sa silhouette épurée lui donne une présence majestueuse.', 'landrover_range_rover_1.jpg', 7, 2024, 'Hybride', 'Automatique', 530),
(20, 'Defender 110', 5200000, 'Nouvelle génération d''une légende. Le Defender 110 conserve l''esprit baroudeur de son aïeul tout en offrant un confort moderne et des capacités tout-terrain exceptionnelles.', 'landrover_defender_1.jpg', 7, 2023, 'Diesel', 'Automatique', 300),
(21, 'Evoque', 3800000, 'Le SUV compact premium. L''Evoque affiche un design urbain soigné, un habitacle raffiné et une conduite douce. Une excellente entrée dans l''univers Range Rover.', 'landrover_evoque_1.jpg', 7, 2024, 'Hybride', 'Automatique', 200)
ON DUPLICATE KEY UPDATE `modele` = VALUES(`modele`), `prix` = VALUES(`prix`), `description` = VALUES(`description`), `image` = VALUES(`image`), `id_marque` = VALUES(`id_marque`), `annee` = VALUES(`annee`), `carburant` = VALUES(`carburant`), `boite` = VALUES(`boite`), `puissance` = VALUES(`puissance`);

-- voiture_image : 126 lignes.
INSERT INTO `voiture_image` (`id_image`, `url`, `id_voiture`) VALUES
(34, 'bmw_m3_1.jpg', 1),
(35, 'bmw_m3_2.jpg', 1),
(36, 'bmw_m3_3.jpg', 1),
(37, 'bmw_m3_4.jpg', 1),
(38, 'bmw_m3_5.jpg', 1),
(39, 'bmw_m3_6.jpg', 1),
(40, 'bmw_m8_1.jpg', 5),
(41, 'bmw_m8_2.jpg', 5),
(42, 'bmw_m8_3.jpg', 5),
(43, 'bmw_m8_4.jpg', 5),
(44, 'bmw_m8_5.jpg', 5),
(45, 'bmw_m8_6.jpg', 5),
(46, 'bmw_serie7_1.jpg', 4),
(47, 'bmw_serie7_2.jpg', 4),
(48, 'bmw_serie7_3.jpg', 4),
(49, 'bmw_serie7_4.jpg', 4),
(50, 'bmw_serie7_5.jpg', 4),
(51, 'bmw_serie7_6.jpg', 4),
(52, 'audi_rs6_1.jpg', 2),
(53, 'audi_rs6_2.jpg', 2),
(54, 'audi_rs6_3.jpg', 2),
(55, 'audi_rs6_4.jpg', 2),
(56, 'audi_rs6_5.jpg', 2),
(57, 'audi_rs6_6.jpg', 2),
(58, 'audi_rs3_1.jpg', 6),
(59, 'audi_rs3_2.jpg', 6),
(60, 'audi_rs3_3.jpg', 6),
(61, 'audi_rs3_4.jpg', 6),
(62, 'audi_rs3_5.jpg', 6),
(63, 'audi_rs3_6.jpg', 6),
(64, 'audi_rs7_1.jpg', 7),
(65, 'audi_rs7_2.jpg', 7),
(66, 'audi_rs7_3.jpg', 7),
(67, 'audi_rs7_4.jpg', 7),
(68, 'audi_rs7_5.jpg', 7),
(69, 'audi_rs7_6.jpg', 7),
(70, 'mercedes_amg_gt_1.jpg', 3),
(71, 'mercedes_amg_gt_2.jpg', 3),
(72, 'mercedes_amg_gt_3.jpg', 3),
(73, 'mercedes_amg_gt_4.jpg', 3),
(74, 'mercedes_amg_gt_5.jpg', 3),
(75, 'mercedes_amg_gt_6.jpg', 3),
(76, 'mercedes_classe_g_1.jpg', 8),
(77, 'mercedes_classe_g_2.jpg', 8),
(78, 'mercedes_classe_g_3.jpg', 8),
(79, 'mercedes_classe_g_4.jpg', 8),
(80, 'mercedes_classe_g_5.jpg', 8),
(81, 'mercedes_classe_g_6.jpg', 8),
(82, 'mercedes_c63_1.jpg', 9),
(83, 'mercedes_c63_2.jpg', 9),
(84, 'mercedes_c63_3.jpg', 9),
(85, 'mercedes_c63_4.jpg', 9),
(86, 'mercedes_c63_5.jpg', 9),
(87, 'mercedes_c63_6.jpg', 9),
(88, 'porsche_911_1.jpg', 10),
(89, 'porsche_911_2.jpg', 10),
(90, 'porsche_911_3.jpg', 10),
(91, 'porsche_911_4.jpg', 10),
(92, 'porsche_911_5.jpg', 10),
(93, 'porsche_911_6.jpg', 10),
(94, 'porsche_cayenne_1.jpg', 11),
(95, 'porsche_cayenne_2.jpg', 11),
(96, 'porsche_cayenne_3.jpg', 11),
(97, 'porsche_cayenne_4.jpg', 11),
(98, 'porsche_cayenne_5.jpg', 11),
(99, 'porsche_cayenne_6.jpg', 11),
(100, 'porsche_taycan_1.jpg', 12),
(101, 'porsche_taycan_2.jpg', 12),
(102, 'porsche_taycan_3.jpg', 12),
(103, 'porsche_taycan_4.jpg', 12),
(104, 'porsche_taycan_5.jpg', 12),
(105, 'porsche_taycan_6.jpg', 12),
(106, 'ferrari_f8_1.jpg', 13),
(107, 'ferrari_f8_2.jpg', 13),
(108, 'ferrari_f8_3.jpg', 13),
(109, 'ferrari_f8_4.jpg', 13),
(110, 'ferrari_f8_5.jpg', 13),
(111, 'ferrari_f8_6.jpg', 13),
(112, 'ferrari_roma_1.jpg', 14),
(113, 'ferrari_roma_2.jpg', 14),
(114, 'ferrari_roma_3.jpg', 14),
(115, 'ferrari_roma_4.jpg', 14),
(116, 'ferrari_roma_5.jpg', 14),
(117, 'ferrari_roma_6.jpg', 14),
(118, 'ferrari_purosangue_1.jpg', 15),
(119, 'ferrari_purosangue_2.jpg', 15),
(120, 'ferrari_purosangue_3.jpg', 15),
(121, 'ferrari_purosangue_4.jpg', 15),
(122, 'ferrari_purosangue_5.jpg', 15),
(123, 'ferrari_purosangue_6.jpg', 15),
(124, 'jeep_wrangler_1.jpg', 16),
(125, 'jeep_wrangler_2.jpg', 16),
(126, 'jeep_wrangler_3.jpg', 16),
(127, 'jeep_wrangler_4.jpg', 16),
(128, 'jeep_wrangler_5.jpg', 16),
(129, 'jeep_wrangler_6.jpg', 16),
(130, 'jeep_grand_cherokee_1.jpg', 17),
(131, 'jeep_grand_cherokee_2.jpg', 17),
(132, 'jeep_grand_cherokee_3.jpg', 17),
(133, 'jeep_grand_cherokee_4.jpg', 17),
(134, 'jeep_grand_cherokee_5.jpg', 17),
(135, 'jeep_grand_cherokee_6.jpg', 17),
(136, 'jeep_compass_1.jpg', 18),
(137, 'jeep_compass_2.jpg', 18),
(138, 'jeep_compass_3.jpg', 18),
(139, 'jeep_compass_4.jpg', 18),
(140, 'jeep_compass_5.jpg', 18),
(141, 'jeep_compass_6.jpg', 18),
(142, 'landrover_range_rover_1.jpg', 19),
(143, 'landrover_range_rover_2.jpg', 19),
(144, 'landrover_range_rover_3.jpg', 19),
(145, 'landrover_range_rover_4.jpg', 19),
(146, 'landrover_range_rover_5.jpg', 19),
(147, 'landrover_range_rover_6.jpg', 19),
(148, 'landrover_defender_1.jpg', 20),
(149, 'landrover_defender_2.jpg', 20),
(150, 'landrover_defender_3.jpg', 20),
(151, 'landrover_defender_4.jpg', 20),
(152, 'landrover_defender_5.jpg', 20),
(153, 'landrover_defender_6.jpg', 20),
(154, 'landrover_evoque_1.jpg', 21),
(155, 'landrover_evoque_2.jpg', 21),
(156, 'landrover_evoque_3.jpg', 21),
(157, 'landrover_evoque_4.jpg', 21),
(158, 'landrover_evoque_5.jpg', 21),
(159, 'landrover_evoque_6.jpg', 21)
ON DUPLICATE KEY UPDATE `url` = VALUES(`url`), `id_voiture` = VALUES(`id_voiture`);

-- services : 8 lignes.
INSERT INTO `services` (`id_services`, `nom_services`, `description_services`, `details_services`, `points_services`, `prix_services`, `image_services`) VALUES
(1, 'Essai personnalisé', 'Réservation d''un essai avec accompagnement par un conseiller SuperCar.', 'Un essai est une étape essentielle avant de choisir votre voiture. L''essai personnalisé vous permet de prendre en main le véhicule de votre choix sur un parcours adapté, en ville comme sur voie rapide.

Un conseiller vous accueille, règle avec vous la position de conduite et répond à toutes vos questions pendant l''essai. Les créneaux sont proposés du lundi au samedi, sur réservation.

L''essai est gratuit et sans engagement : c''est le meilleur moyen de vérifier que la voiture correspond vraiment à votre usage quotidien.', 'Essai gratuit et sans engagement
Parcours ville et voie rapide
Conseiller dédié pendant l''essai', 0, 'service_essai.jpg'),
(2, 'Conseil d''achat', 'Accompagnement du client dans le choix du véhicule adapté à ses besoins.', 'Acheter une voiture premium est un investissement : notre rôle est de vous aider à faire le bon choix, pas de vous vendre à tout prix.

Nous analysons avec vous votre budget, votre usage (ville, route, famille) et vos préférences (motorisation, boîte de vitesses, équipements), puis nous comparons ensemble les modèles du catalogue.

Vous repartez avec un avis clair et chiffré, en toute transparence sur les prix et les caractéristiques de chaque véhicule.', 'Analyse de vos besoins et de votre budget
Comparatif transparent des modèles
Avis clair et chiffré', 0, 'service_conseil.jpg'),
(3, 'Livraison à domicile', 'Livraison du véhicule au domicile du client après validation de l''achat.', 'Votre voiture vous est remise où vous voulez, dans un état irréprochable.

Avant la livraison, le véhicule est entièrement préparé : nettoyage complet, contrôle des niveaux et vérification des équipements. Le jour J, nous vous remettons les clés en mains propres, avec tous les documents du véhicule.

Nous prenons le temps de vous expliquer la prise en main : réglages, équipements et conseils d''entretien pour bien démarrer.', 'Véhicule préparé et contrôlé
Remise des clés en main propre
Explications pour la prise en main', 5000, 'service_livraison.jpg'),
(4, 'Démarches administratives', 'Aide pour l''immatriculation du véhicule et accompagnement dans les démarches liées à l''assurance.', 'L''immatriculation et l''assurance d''un véhicule prennent du temps : SuperCar s''en occupe pour vous.

Nous constituons avec vous le dossier complet, effectuons les démarches d''immatriculation et vous accompagnons dans le choix de l''assurance adaptée à votre profil et à votre véhicule.

Vous êtes tenu informé à chaque étape, jusqu''à la remise de l''ensemble des documents.', 'Dossier constitué avec vous
Immatriculation effectuée
Assurance adaptée proposée', 0, 'service_demarche.jpg'),
(5, 'Reprise de votre ancien véhicule', 'Estimation offerte de votre véhicule actuel, déduite directement du prix de votre nouvelle voiture.', 'Pas besoin de vendre votre voiture vous-même : SuperCar reprend votre véhicule actuel au juste prix.

Nous l''inspectons avec vous en 30 minutes (état, kilométrage, entretien) puis nous vous remettons une offre écrite sous 24 heures.

Le montant de la reprise est déduit directement du prix de votre nouvelle voiture : vous ne payez que la différence.', 'Inspection gratuite de 30 minutes
Offre écrite sous 24 heures
Montant déduit du prix d''achat', 0, 'service_reprise.jpg'),
(6, 'Financement sur mesure', 'Simulation claire avec nos partenaires : crédit classique ou LOA, sans surprise.', 'Un projet auto doit rester un plaisir, pas un casse-tête. Nous étudions avec vous la formule adaptée : apport, durée et mensualités, en crédit classique comme en location avec option d''achat.

La simulation prend 15 minutes et la réponse de principe arrive rapidement, sans engagement de votre part.

Tous les chiffres sont posés noir sur blanc avant que vous signiez quoi que ce soit.', 'Simulation gratuite en 15 minutes
Crédit ou LOA au choix
Réponse de principe rapide', 0, 'service_financement.jpg'),
(7, 'Garantie 12 mois', 'Roulez serein : moteur, boîte de vitesses et transmission couverts pendant un an.', 'Chaque voiture SuperCar est contrôlée avant la vente, et la garantie 12 mois prend le relais après la livraison.

Sont couverts : le moteur, la boîte de vitesses et la transmission, pièces et main-d''œuvre incluses, dans notre atelier.

En cas de panne couverte, nous réparons sans franchise et vous tenons informé à chaque étape.', 'Moteur, boîte et transmission couverts
Pièces et main-d''œuvre incluses
Valable 12 mois après la livraison', 15000, 'service_garantie.jpg'),
(8, 'Detailing & entretien', 'Nettoyage premium, polissage et contrôle 50 points par nos techniciens.', 'Nos techniciens bichonnent votre voiture comme au premier jour : nettoyage complet intérieur et extérieur, polissage de la carrosserie et lustrage.

Chaque passage inclut notre contrôle 50 points (niveaux, freins, pneus, éclairage, électronique) pour rouler en toute confiance.

Comptez une journée d''immobilisation : nous vous prévenons dès que votre voiture est prête.', 'Nettoyage intérieur et extérieur complet
Polissage et lustrage carrosserie
Contrôle 50 points inclus', 4500, 'service_detailing.jpg')
ON DUPLICATE KEY UPDATE `nom_services` = VALUES(`nom_services`), `description_services` = VALUES(`description_services`), `details_services` = VALUES(`details_services`), `points_services` = VALUES(`points_services`), `prix_services` = VALUES(`prix_services`), `image_services` = VALUES(`image_services`);

-- contenu_accueil : 1 lignes.
INSERT INTO `contenu_accueil` (`id_contenu`, `hero_surtitre`, `hero_titre_ligne1`, `hero_titre_ligne2`, `hero_description`, `cta_titre`, `cta_description`, `date_modification`) VALUES
(1, 'Concessionnaire premium - depuis 2009', 'Découvrez votre', 'prochaine voiture.', 'Performance, luxe et innovation. SuperCar sélectionne des véhicules d''exception et vous accompagne du premier essai à la livraison.', 'Envie de tester une voiture ?', 'Réservez votre essai en quelques clics et vivez une expérience de conduite unique.', '2026-10-07 10:31:35')
ON DUPLICATE KEY UPDATE `hero_surtitre` = VALUES(`hero_surtitre`), `hero_titre_ligne1` = VALUES(`hero_titre_ligne1`), `hero_titre_ligne2` = VALUES(`hero_titre_ligne2`), `hero_description` = VALUES(`hero_description`), `cta_titre` = VALUES(`cta_titre`), `cta_description` = VALUES(`cta_description`), `date_modification` = VALUES(`date_modification`);

-- contenu_contact : 46 lignes.
INSERT INTO `contenu_contact` (`cle`, `valeur`, `date_modification`) VALUES
('adresse', '58–60, Cybercité', '2026-10-13 20:43:20'),
('adresse_label', 'Notre adresse', '2026-10-13 20:43:20'),
('carte_accessibilite', 'Localisation de SuperCar à Ébène, Île Maurice', '2026-10-13 20:43:20'),
('carte_description', 'Retrouvez-nous au cœur de la Cybercité, à Ébène.', '2026-10-13 20:43:20'),
('carte_recherche', '-20.2463642,57.4892986', '2026-10-13 20:52:48'),
('carte_surtitre', 'Venez nous rencontrer', '2026-10-13 20:43:20'),
('carte_titre', 'Votre prochaine étape : SuperCar', '2026-10-13 20:43:20'),
('carte_zoom', '16', '2026-10-13 20:43:20'),
('champs_note', 'Tous les champs sont obligatoires.', '2026-10-13 20:43:20'),
('coordonnees_intro', 'En concession, par téléphone ou par e-mail : échangeons comme vous le souhaitez.', '2026-10-13 20:43:20'),
('coordonnees_titre', 'Rencontrons-nous', '2026-10-13 20:43:20'),
('email', 'contact@supercar.mu', '2026-10-13 20:43:20'),
('email_form_label', 'Adresse e-mail', '2026-10-13 20:43:20'),
('email_label', 'Écrivez-nous', '2026-10-13 20:43:20'),
('email_placeholder', 'vous@exemple.com', '2026-10-13 20:43:20'),
('envoi_bouton', 'Envoyer mon message', '2026-10-13 20:43:20'),
('erreur_email', 'Saisissez une adresse e-mail valide.', '2026-10-13 20:46:47'),
('erreur_message', 'Saisissez votre message (5 000 caractères maximum).', '2026-10-13 20:46:47'),
('erreur_nom', 'Saisissez votre nom complet (100 caractères maximum).', '2026-10-13 20:46:47'),
('erreur_session', 'Votre session a expiré. Veuillez réessayer.', '2026-10-13 20:46:47'),
('erreur_titre', 'Votre message n’a pas été envoyé.', '2026-10-13 20:46:47'),
('form_intro', 'Présentez-nous votre projet. Nous prendrons le temps de vous répondre.', '2026-10-13 20:43:20'),
('form_note', 'Votre message est enregistré pour permettre à l’équipe SuperCar de vous répondre.', '2026-10-13 20:43:20'),
('form_surtitre', 'Écrivez-nous', '2026-10-13 20:43:20'),
('form_titre', 'Comment pouvons-nous vous aider ?', '2026-10-13 20:43:20'),
('horaires_complement', '', '2026-10-13 20:43:20'),
('horaires_label', 'Nos horaires', '2026-10-13 20:43:20'),
('horaires_samedi', 'Samedi : 09 h – 13 h', '2026-10-13 20:43:20'),
('horaires_semaine', 'Lun. – ven. : 08 h – 18 h', '2026-10-13 20:43:20'),
('introduction', 'Un véhicule vous intéresse ? Une question sur nos services ? Notre équipe vous accompagne, du premier échange à la livraison.', '2026-10-13 20:43:20'),
('itineraire_bouton', 'Préparer mon itinéraire', '2026-10-13 20:43:20'),
('message_label', 'Votre message', '2026-10-13 20:43:20'),
('message_placeholder', 'Parlez-nous du véhicule ou du service qui vous intéresse…', '2026-10-13 20:43:20'),
('nom_label', 'Nom complet', '2026-10-13 20:43:20'),
('nom_placeholder', 'Votre nom et votre prénom', '2026-10-13 20:43:20'),
('page_titre', 'Contact', '2026-10-13 20:43:20'),
('pays', 'Île Maurice', '2026-10-13 20:43:20'),
('succes_message', 'Merci ! Votre message a bien été enregistré. Notre équipe vous répondra rapidement.', '2026-10-13 20:43:20'),
('surtitre', 'À votre écoute', '2026-10-13 20:43:20'),
('telephone', '+230 212 0000', '2026-10-13 20:43:20'),
('telephone_label', 'Appelez-nous', '2026-10-13 20:43:20'),
('telephone_secondaire', '+230 5250 0000', '2026-10-13 20:43:20'),
('titre', 'Parlons de votre prochain projet.', '2026-10-13 20:43:20'),
('ville', 'Ébène', '2026-10-13 20:43:20'),
('visite_bouton', 'Demande d’essais', '2026-10-13 20:43:20'),
('visite_note', 'Pour un essai, pensez à réserver votre créneau.', '2026-10-13 20:43:20')
ON DUPLICATE KEY UPDATE `valeur` = VALUES(`valeur`), `date_modification` = VALUES(`date_modification`);

COMMIT;
