<?php

require '../vendor/autoload.php';
require '../app/core/bootstrap.php';

$router = new Router;

require '../app/routes.php';

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Usuń prefiks katalogu bazowego, jeśli jest obecny w URI
$baseUri = '2fatest';
if (strpos($uri, $baseUri) === 0) {
    $uri = substr($uri, strlen($baseUri));
}

// Usuń wiodący ukośnik, jeśli jest obecny
$uri = ltrim($uri, '/');

// Debugging: wypisz przetworzony URI
echo "Przetworzony URI w index.php: '$uri'<br>";

$router->direct($uri, $_SERVER['REQUEST_METHOD']);
