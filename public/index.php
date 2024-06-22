<?php

require_once __DIR__ . '/../app/core/bootstrap.php';
require_once __DIR__ . '/../app/core/Router.php';

$router = new Router();

require_once __DIR__ . '/../app/routes.php';

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$baseUri = '2fatest';
if (strpos($uri, $baseUri) === 0) {
    $uri = substr($uri, strlen($baseUri));
}

$uri = ltrim($uri, '/');

$router->direct($uri, $_SERVER['REQUEST_METHOD']);
