<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', "Strict");
ini_set('session.cookie_secure', 1);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ob_start();
session_start();

//database credentials
define('DBHOST', 'localhost');
define('DBUSER', 'u887924200_smriti');
define('DBPASS', '2sdf@)s#');
define('DBNAME', 'u887924200_database');

$db = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$hashOptions = [
    'memory_cost' => 1 << 17, // 128 Mb
    'time_cost'   => 3,
    'threads'     => 3,
];

define('SITE_ADDR', "https://smritidangwal.com");
define('SITE_EMAIL', "contact@smritidangwal.com");
define('SITE_TITLE', "Travel The World");
define('AJAX_REQUEST', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
define('ACTIVATION_TIME_DIFFERENCE', 20);

date_default_timezone_set('Europe/Dublin');

$nonce = base64_encode(random_bytes(20));
$headerCSP = "Content-Security-Policy:" .
    "base-uri 'self';" . // HTML Injections
    "connect-src 'self' https:;" . // XMLHttpRequest (AJAX request), WebSocket or EventSource.
    "default-src 'self' https:;" . // Default policy for loading html elements
    "font-src 'self' https://code.ionicframework.com https://fonts.gstatic.com;" . // Valid Font Source
    "frame-ancestors 'self';" . //allow parent framing - this one blocks click jacking and ui redress
    "frame-src 'self' https://www.google.com/recaptcha/;" . // valid frame and iframe sources
    "form-action 'self';" . // valid form-actions
    "img-src 'self' data:;" . // valid image sources and favicons
    "media-src 'self';" . // vaid sources for media (audio and video html tags src)
    "object-src 'none'; " . // valid object embed and applet tags src
    "script-src 'self' 'nonce-" . $nonce . "' https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/;" . // allows js from self, jquery and google analytics.  Inline allows inline js
    "style-src 'self' https://code.ionicframework.com https://fonts.googleapis.com;"; // allows css from self, google and no inline css
header($headerCSP);
