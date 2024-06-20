<?php

$router->get('', 'HomeController@home');
$router->get('login', 'LoginAndVerifyController@login');
$router->post('login', 'LoginAndVerifyController@handleLogin');
$router->get('verify_login_totp', 'LoginAndVerifyController@verifyLoginTotp');
$router->post('verify_login_totp', 'LoginAndVerifyController@handleVerifyLoginTotp');
$router->get('register', 'RegisterAndVerifyController@register');
$router->post('register', 'RegisterAndVerifyController@handleRegister');
$router->get('verify', 'RegisterAndVerifyController@verify');
$router->post('verify', 'RegisterAndVerifyController@handleVerify');
$router->get('dashboard', 'UserController@dashboard');
$router->get('new_transfer', 'TransfersController@newTransfer');
$router->post('new_transfer', 'TransfersController@handleNewTransfer');
$router->get('confirm_password', 'TransfersController@confirmPassword');
$router->post('confirm_password', 'TransfersController@handleConfirmPassword');
$router->get('confirm_transfer', 'TransfersController@confirmTransfer');
$router->post('confirm_transfer', 'TransfersController@handleConfirmTransfer');
$router->get('account', 'OtherControllers@account');
$router->get('settings', 'OtherControllers@settings');
$router->post('settings', 'OtherControllers@handleSettings');
$router->get('hidden/zboczuch', 'HiddenController@zboczuch');
$router->get('hidden/parararara', 'HiddenController@parararara');
$router->get('hidden/papaj', 'HiddenController@papaj');
$router->get('all_operations', 'AuditorController@allOperations');
$router->get('manage_users', 'AdminController@manageUsers');
$router->post('reset_password', 'AdminController@resetPassword');
$router->get('logout', 'UserController@logout');
$router->get('reset_password', 'UserController@resetPasswordForm');
$router->post('reset_password_action', 'UserController@handleResetPassword');
$router->get('test', 'TestController@test');

// Add routes for images
$router->get('images/{imageName}', function($imageName) {
    $imagePath = __DIR__ . "/../public/images/$imageName";
    if (file_exists($imagePath)) {
        $imageInfo = getimagesize($imagePath);
        header("Content-Type: " . $imageInfo['mime']);
        readfile($imagePath);
        exit();
    } else {
        http_response_code(404);
        echo "Image not found.";
        exit();
    }
});
