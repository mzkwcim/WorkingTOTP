<?php

require_once __DIR__ . '/../../vendor/autoload.php';

spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/../';

    $class = str_replace('\\', '/', $class);

    $paths = [
        $base_dir . 'controllers/',
        $base_dir . 'controllers/user/',
        $base_dir . 'controllers/admin/',
        $base_dir . 'controllers/auditor/',
        $base_dir . 'models/',
        $base_dir . 'core/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});
