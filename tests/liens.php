<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit(); }
$base = 'http://localhost/SiteWeb%20Supercar/';
$file = ['index.php', 'voitures.php', 'services.php', 'presentation.php', 'contact.php', 'login.php', 'register.php', 'detail.php?id=1', 'service.php?id=1'];
$vus = []; $assets = []; $erreurs = []; $ancres = [];
while ($file) {
    $page = array_shift($file);
    if (isset($vus[$page])) continue;
    if (count($vus) > 250) throw new RuntimeException('Limite du parcours atteinte.');
    $curl = curl_init($base . $page);
    curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 15]);
    $html = curl_exec($curl); $code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE); curl_close($curl);
    $vus[$page] = $code;
    if ($code >= 400 || $code === 0) { $erreurs[] = $page . ' : HTTP ' . $code; continue; }
    if ($code >= 300) continue;
    $dom = new DOMDocument(); libxml_use_internal_errors(true); $dom->loadHTML('<?xml encoding="UTF-8">' . $html); libxml_clear_errors();
    $xp = new DOMXPath($dom);
    foreach ($xp->query('//a[@href]|//img[@src]|//script[@src]|//link[@rel="stylesheet"]') as $node) {
        $url = $node->getAttribute($node->hasAttribute('href') ? 'href' : 'src');
        if (preg_match('~^(?:[a-z]+:|//)~i', $url)) continue;
        $chemin = parse_url($url, PHP_URL_PATH) ?: '';
        if ($chemin === '') $cible = $page;
        else $cible = (dirname(parse_url($page, PHP_URL_PATH)) === '.' ? '' : dirname(parse_url($page, PHP_URL_PATH)) . '/') . $chemin . (($q = parse_url($url, PHP_URL_QUERY)) ? '?' . $q : '');
        $segments = [];
        foreach (explode('/', parse_url($cible, PHP_URL_PATH)) as $segment) {
            if ($segment === '..') array_pop($segments);
            elseif ($segment !== '' && $segment !== '.') $segments[] = $segment;
        }
        $cible = implode('/', $segments) . (($q = parse_url($cible, PHP_URL_QUERY)) ? '?' . $q : '');
        $fragment = parse_url($url, PHP_URL_FRAGMENT);
        if ($fragment) $ancres[] = [$cible, $fragment];
        if (str_ends_with($chemin, '.php') || $chemin === '') { if (!isset($vus[$cible])) $file[] = $cible; }
        else $assets[$cible] = true;
    }
    foreach ($ancres as [$cible, $fragment]) {
        if ($cible === $page && !$xp->query('//*[@id=' . "'" . str_replace("'", '', $fragment) . "'" . ']')->length) $erreurs[] = $page . '#' . $fragment . ' : ancre absente';
    }
}
// Contrôle des ressources et de la casse, y compris les fonds CSS.
preg_match_all('~url\([\x22\x27]?([^\)\x22\x27]+)~', file_get_contents(__DIR__ . '/../style.css'), $fonds);
foreach ($fonds[1] as $fond) if (!str_starts_with($fond, 'data:') && !str_contains($fond, '://')) $assets[$fond] = true;
foreach (array_keys($assets) as $asset) {
    $chemin = rawurldecode(parse_url($asset, PHP_URL_PATH));
    $absolu = __DIR__ . '/../' . $chemin;
    if (!is_file($absolu) || !in_array(basename($absolu), scandir(dirname($absolu)), true)) $erreurs[] = $asset . ' : fichier absent ou casse incorrecte';
}
$proteges = ['table.sql', 'schema.sql', 'config.local.php', '.git/config', 'includes/db.php', 'tests/.audit-state.json', 'README.md', 'admin/includes/auth.php'];
foreach ($proteges as $path) {
    $curl = curl_init($base . $path); curl_setopt_array($curl, [CURLOPT_NOBODY => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10]); curl_exec($curl); $code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE); curl_close($curl);
    if (!in_array($code, [403, 404], true)) $erreurs[] = $path . ' : ressource privée accessible';
}
file_put_contents(__DIR__ . '/.audit-links.json', json_encode(['pages' => count($vus), 'ressources' => count($assets), 'protections' => count($proteges), 'erreurs' => $erreurs], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo count($vus) . ' URL, ' . count($assets) . ' ressources locales, ' . count($proteges) . ' protections HTTP.' . PHP_EOL;
if ($erreurs) { echo implode(PHP_EOL, array_unique($erreurs)) . PHP_EOL; exit(1); }
echo 'Aucun lien interne, ancre ou fichier manquant détecté.' . PHP_EOL;
