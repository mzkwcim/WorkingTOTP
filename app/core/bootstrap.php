<?php

// Autoloading funkcji
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/../controllers/';

    // Zamień backslashes na slashes w namespace
    $class = str_replace('\\', '/', $class);

    // Sprawdź ścieżkę w katalogu controllers
    $file = $base_dir . $class . '.php';
    if (file_exists($file)) {
        require $file;
        return;
    }

    // Sprawdź ścieżkę w podkatalogu user
    $file_user = $base_dir . 'user/' . $class . '.php';
    if (file_exists($file_user)) {
        require $file_user;
        return;
    }

    // Sprawdź ścieżkę w podkatalogu auditor
    $file_auditor = $base_dir . 'auditor/' . $class . '.php';
    if (file_exists($file_auditor)) {
        require $file_auditor;
        return;
    }

    // Sprawdź ścieżkę w podkatalogu admin
    $file_admin = $base_dir . 'admin/' . $class . '.php';
    if (file_exists($file_admin)) {
        require $file_admin;
        return;
    }

    // Sprawdź ścieżkę w katalogu models
    $base_dir_model = __DIR__ . '/../models/';
    $file_model = $base_dir_model . $class . '.php';
    if (file_exists($file_model)) {
        require $file_model;
        return;
    }

    // Sprawdź ścieżkę w katalogu core
    $base_dir_core = __DIR__ . '/../core/';
    $file_core = $base_dir_core . $class . '.php';
    if (file_exists($file_core)) {
        require $file_core;
    }
});
