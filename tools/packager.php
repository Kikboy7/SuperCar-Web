<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit(); }
if (!class_exists('ZipArchive')) { fwrite(STDERR, "L’extension ZIP de PHP est nécessaire.\n"); exit(1); }
$sortie = $argv[1] ?? '';
if ($sortie === '' || is_file($sortie)) { fwrite(STDERR, "Indiquer le chemin d’un NOUVEAU fichier ZIP.\n"); exit(1); }
$racine = realpath(__DIR__ . '/..');
$zip = new ZipArchive();
if ($zip->open($sortie, ZipArchive::CREATE) !== true) throw new RuntimeException('Archive impossible.');
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($racine, FilesystemIterator::SKIP_DOTS)) as $fichier) {
    if (!$fichier->isFile()) continue;
    $relatif = str_replace('\\', '/', substr($fichier->getPathname(), strlen($racine) + 1));
    $autorise = preg_match('~^(?:admin/|includes/|images/)~', $relatif) || (dirname($relatif) === '.' && (str_ends_with($relatif, '.php') || in_array($relatif, ['style.css', '.htaccess', 'HEBERGEMENT.md'], true))) || in_array($relatif, ['tools/creer-admin.php', 'tools/.htaccess'], true);
    if (!$autorise || $relatif === 'config.local.php' || str_ends_with($relatif, '.md') && $relatif !== 'HEBERGEMENT.md') continue;
    $zip->addFile($fichier->getPathname(), $relatif);
}
$zip->close();
echo "Paquet créé sans configuration privée, SQL, dossier Git ni données de test. Les deux imports SQL sont fournis séparément.\n";
