<?php
/** Configuration privée, erreurs et session : aucun HTML n’est envoyé ici. */
if (defined('SUPERCAR_BOOTSTRAPPED')) return;
define('SUPERCAR_BOOTSTRAPPED', true);
$configurationLocale = is_file(__DIR__ . '/../config.local.php') ? require __DIR__ . '/../config.local.php' : [];
function configuration($cle, $defaut = null) {
    global $configurationLocale;
    $env = getenv('SUPERCAR_' . strtoupper($cle));
    return $env !== false ? $env : ($configurationLocale[$cle] ?? $defaut);
}
$environnement = configuration('env', 'production');
error_reporting(E_ALL);
ini_set('display_errors', $environnement === 'local' ? '1' : '0');
ini_set('log_errors', '1');
date_default_timezone_set(configuration('timezone', 'Indian/Mauritius'));
if ($environnement !== 'local') {
    set_exception_handler(function (Throwable $exception) {
        error_log('SuperCar : ' . $exception);
        if (PHP_SAPI === 'cli') { fwrite(STDERR, "Une erreur est survenue. Consultez le journal privé du serveur.\n"); exit(1); }
        http_response_code(500);
        echo '<p>Une erreur est survenue. Veuillez réessayer plus tard.</p>';
    });
}
if (PHP_SAPI !== 'cli' && !headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: frame-ancestors 'self'; base-uri 'self'; form-action 'self'; object-src 'none'");
    if ($environnement === 'local' && str_starts_with((string) configuration('db_name'), 'supercar_recette_')) {
        header('X-SuperCar-Recette: ' . hash('sha256', configuration('db_name')));
    }
    if (session_status() === PHP_SESSION_NONE) {
        session_name('SUPERCARSESSID');
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => filter_var(configuration('cookie_secure', !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'), FILTER_VALIDATE_BOOLEAN), 'path' => '/']);
        session_start();
    }
}
