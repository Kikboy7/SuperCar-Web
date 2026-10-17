<?php
// Copier ce fichier en config.local.php SUR LE SERVEUR ; ne jamais le committer.
// Les variables SUPERCAR_ENV, SUPERCAR_DB_HOST, etc. sont prioritaires.
return [
    'env' => 'production',
    'db_host' => 'localhost',
    'db_port' => '3306',
    'db_name' => 'nom_fourni_par_hebergeur',
    'db_user' => 'utilisateur_dedie',
    'db_password' => 'A_RENSEIGNER_SUR_LE_SERVEUR',
    'timezone' => 'Indian/Mauritius',
    'cookie_secure' => true, // HTTPS obligatoire en production.
];
