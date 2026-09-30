USE supercar;

-- Cette migration ajoute la gestion des textes de la page d'accueil
-- sans supprimer les données déjà présentes dans la base.
CREATE TABLE IF NOT EXISTS contenu_accueil (
    id_contenu TINYINT PRIMARY KEY,
    hero_surtitre VARCHAR(150) NOT NULL,
    hero_titre_ligne1 VARCHAR(150) NOT NULL,
    hero_titre_ligne2 VARCHAR(150) NOT NULL,
    hero_description TEXT NOT NULL,
    cta_titre VARCHAR(150) NOT NULL,
    cta_description TEXT NOT NULL,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT IGNORE INTO contenu_accueil (
    id_contenu,
    hero_surtitre,
    hero_titre_ligne1,
    hero_titre_ligne2,
    hero_description,
    cta_titre,
    cta_description
) VALUES (
    1,
    'Concessionnaire premium - depuis 2009',
    'Découvrez votre',
    'prochaine voiture.',
    'Performance, luxe et innovation. SuperCar sélectionne des véhicules d''exception et vous accompagne du premier essai à la livraison.',
    'Envie de tester une voiture ?',
    'Réservez votre essai en quelques clics et vivez une expérience de conduite unique.'
);

-- Corriger les textes déjà enregistrés dans la base actuelle.
UPDATE voiture SET modele = 'Série 7' WHERE id_voiture = 4;
UPDATE voiture SET carburant = 'Électrique' WHERE id_voiture = 12;

UPDATE voiture SET description = 'La BMW M3 Competition incarne la berline sportive par excellence : un moteur six cylindres biturbo, une transmission intégrale M xDrive et un châssis réglé sur mesure pour une conduite aussi précise qu''exaltante. Son design agressif, son habitacle premium et ses technologies de pointe la placent au sommet de sa catégorie.' WHERE id_voiture = 1;
UPDATE voiture SET description = 'La référence des sportives. La Porsche 911 Turbo S conjugue un flat-six biturbo de 650 ch, une adhérence absolue et un raffinement incomparable. Elle passe de 0 à 100 km/h en 2,7 secondes, pour un plaisir de conduite sans égal.' WHERE id_voiture = 10;
UPDATE voiture SET description = 'Le SUV le plus sportif du monde. Le Cayenne Turbo GT associe 660 ch, un châssis abaissé et un dynamisme de supercar. Le confort d''un grand SUV et le tempérament d''une sportive.' WHERE id_voiture = 11;
UPDATE voiture SET description = 'L''électrique n''a jamais été aussi sportive. La Taycan Turbo S développe jusqu''à 761 ch grâce à son groupe motopropulseur à deux moteurs. Recharge rapide, silence absolu et accélération fulgurante.' WHERE id_voiture = 12;
UPDATE voiture SET description = 'L''hommage au V8 le plus victorieux de l''histoire. La F8 Tributo associe 720 ch à une aérodynamique parfaitement maîtrisée. Un chef-d''œuvre italien, à la fois sculptural et ultraperformant.' WHERE id_voiture = 13;
UPDATE voiture SET description = 'La dolce vita. La Ferrari Roma capture l''esprit des voitures de grand tourisme des années 1960 : lignes élégantes, habitacle somptueux et moteur V8 de 620 ch. L''alliance parfaite du style et de la performance.' WHERE id_voiture = 14;
UPDATE voiture SET description = 'Le premier SUV de l''histoire de Ferrari. Le Purosangue est propulsé par le célèbre V12 atmosphérique de 725 ch. Performance absolue, élégance italienne et confort de voyage.' WHERE id_voiture = 15;
UPDATE voiture SET description = 'Le mythe américain du tout-terrain. Le Wrangler Rubicon est équipé d''essieux renforcés, de ponts rigides et d''une transmission 4x4 conçue pour les franchissements les plus difficiles.' WHERE id_voiture = 16;
UPDATE voiture SET description = 'Luxe, confort et capacités tout-terrain. Le Grand Cherokee offre un habitacle premium, des technologies de pointe et une motorisation hybride efficace pour les longs voyages.' WHERE id_voiture = 17;
UPDATE voiture SET description = 'Compact et polyvalent, le Jeep Compass conjugue agilité urbaine et capacités tout-terrain, avec un design musclé et un habitacle connecté.' WHERE id_voiture = 18;
UPDATE voiture SET description = 'Le SUV britannique par excellence. Le Range Rover Sport marie raffinement, technologies de dernière génération et capacités tout-terrain remarquables. Sa silhouette épurée lui donne une présence majestueuse.' WHERE id_voiture = 19;
UPDATE voiture SET description = 'Le break sportif de référence. L''Audi RS6 Avant combine un V8 biturbo de 600 ch à un habitacle spacieux et raffiné. Son châssis adaptatif et sa transmission quattro assurent une tenue de route exemplaire, même sur les longs trajets.' WHERE id_voiture = 2;
UPDATE voiture SET description = 'Nouvelle génération d''une légende. Le Defender 110 conserve l''esprit baroudeur de son aïeul tout en offrant un confort moderne et des capacités tout-terrain exceptionnelles.' WHERE id_voiture = 20;
UPDATE voiture SET description = 'Le SUV compact premium. L''Evoque affiche un design urbain soigné, un habitacle raffiné et une conduite douce. Une excellente entrée dans l''univers Range Rover.' WHERE id_voiture = 21;
UPDATE voiture SET description = 'La sportive deux places signée Mercedes-AMG. L''AMG GT offre un V8 biturbo de 585 ch, un châssis réglable et un design racé qui ne laisse personne indifférent. Un concentré d''adrénaline et de luxe.' WHERE id_voiture = 3;
UPDATE voiture SET description = 'La grande berline de luxe signée BMW. La Série 7 offre un confort absolu avec ses sièges massants, son écran cinéma arrière et son système audio Bowers & Wilkins. Sa motorisation hybride rechargeable allie élégance, silence et respect de l''environnement.' WHERE id_voiture = 4;
UPDATE voiture SET description = 'Le summum du grand tourisme sportif allemand. La BMW M8 Competition associe un V8 biturbo de 625 ch à un coupé élégant et à une technologie de pointe. Son habitacle luxueux en cuir Merino accompagne une accélération de 0 à 100 km/h en 3,2 secondes.' WHERE id_voiture = 5;
UPDATE voiture SET description = 'Compacte parmi les plus performantes de sa catégorie, l''Audi RS3 Sportback embarque un moteur cinq cylindres de 400 ch au caractère affirmé, une sonorité unique et une agilité redoutable. Elle est parfaite pour la ville comme pour les routes sinueuses.' WHERE id_voiture = 6;
UPDATE voiture SET description = 'Cette berline cinq portes au style coupé marie élégance et performance. L''Audi RS7 Sportback séduit par son V8 de 600 ch, sa ligne fluide et ses technologies d''aide à la conduite de dernière génération.' WHERE id_voiture = 7;
UPDATE voiture SET description = 'L''icône absolue du tout-terrain de luxe. La Classe G conserve sa silhouette carrée légendaire tout en offrant un confort exceptionnel. Ses trois blocages de différentiel lui permettent de dompter les terrains les plus extrêmes.' WHERE id_voiture = 8;
UPDATE voiture SET description = 'Motorisations électrique et thermique réunies : la C 63 AMG affiche 680 ch dans une berline au style compact. Racée et technologique, elle offre une expérience de conduite hybride à hautes performances.' WHERE id_voiture = 9;

UPDATE services
SET nom_services = 'Essai personnalisé',
    description_services = 'Réservation d''un essai avec accompagnement par un conseiller SuperCar.'
WHERE id_services = 1;

UPDATE services
SET nom_services = 'Conseil d''achat',
    description_services = 'Accompagnement du client dans le choix du véhicule adapté à ses besoins.'
WHERE id_services = 2;

UPDATE services
SET nom_services = 'Livraison à domicile',
    description_services = 'Livraison du véhicule au domicile du client après validation de l''achat.'
WHERE id_services = 3;

INSERT INTO services (nom_services, description_services, prix_services)
SELECT
    'Démarches administratives',
    'Aide pour l''immatriculation du véhicule et accompagnement dans les démarches liées à l''assurance.',
    0
WHERE NOT EXISTS (
    SELECT 1 FROM services WHERE nom_services = 'Démarches administratives'
);
