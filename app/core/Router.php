<?php

class Router {
    protected $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get($uri, $controller) {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller) {
        $this->routes['POST'][$uri] = $controller;
    }

    public function direct($uri, $requestType) {
        // Obsługa plików statycznych (CSS, JS, obrazy)
        if (preg_match('/\.(css|js|jpg|jpeg|png|gif|svg)$/', $uri)) {
            return $this->serveStaticFile($uri);
        }

        if (array_key_exists($uri, $this->routes[$requestType])) {
            return $this->callAction(
                ...explode('@', $this->routes[$requestType][$uri])
            );
        }

        throw new Exception("No route defined for this URI: '$uri'");
    }

    protected function callAction($controller, $action) {
        $controller = new $controller;

        if (!method_exists($controller, $action)) {
            throw new Exception(
                "{$controller} does not respond to the {$action} action."
            );
        }

        return $controller->$action();
    }

    protected function serveStaticFile($uri) {
        $file = __DIR__ . '/../../public/' . $uri;
        if (file_exists($file)) {
            $fileInfo = pathinfo($file);
            $mimeType = $this->getMimeType($fileInfo['extension']);
            header("Content-Type: $mimeType");
            readfile($file);
            exit;
        } else {
            http_response_code(404);
            echo "File not found.";
            exit;
        }
    }

    protected function getMimeType($extension) {
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
        ];
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    public static function load($file) {
        $router = new static;

        require $file;

        return $router;
    }
}
