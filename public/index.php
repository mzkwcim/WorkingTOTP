<?php
// Załaduj plik konfiguracyjny bazy danych
require_once '../db.php';

// Autoloading klas
spl_autoload_register(function ($class) {
    $file = '../app/controllers/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Pobierz URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

// Prosta obsługa trasowania
switch ($url) {
    case 'login':
        require_once '../app/views/login.php';
        break;
    case 'register':
        $controller = new UserController();
        $controller->register();
        break;
    case 'store':
        $controller = new UserController();
        $controller->store();
        break;
    case 'verify_login_totp':
        require_once '../app/views/verify_login_totp.php';
        break;
    case 'verify':
        require_once '../app/views/verify.php';
        break;
    case 'dashboard':
        require_once '../app/views/user/dashboard.php';
        break;
    case 'new_transfer':
        require_once '../app/views/user/new_transfer.php';
        break;
    case 'account':
        require_once '../app/views/user/account.php';
        break;
    case 'settings':
        require_once '../app/views/user/settings.php';
        break;
    case 'logout':
        require_once '../app/views/user/logout.php';
        break;
    case 'confirm_password':
        require_once '../app/views/user/confirm_password.php';
        break;
    case 'confirm_transfer':
        require_once '../app/views/user/confirm_transfer.php';
        break;
    case 'papaj':
        require_once '../app/views/papaj.php';
        break;
    case 'parararara':
        require_once '../app/views/parararara.php';
        break;
    case 'zboczuch':
        require_once '../app/views/zboczuch.php';
        break;
    case 'manage_users':
        require_once '../app/views/admin/manage_users.php';
        break;
    case 'all_operations':
        require_once '../app/views/auditor/all_operations.php';
        break;
    case 'reset_password':
        require_once '../app/views/user/reset_password.php';
        break;
    default:
        require_once '../app/views/home.php';
        break;
}
