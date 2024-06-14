<?php
require 'db.php';
require 'vendor/autoload.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

function validate_password($password) {
    if (strlen($password) < 12) {
        return "Hasło musi mieć co najmniej 12 znaków.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return "Hasło musi zawierać co najmniej jedną wielką literę.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        return "Hasło musi zawierać co najmniej jedną małą literę.";
    }
    if (!preg_match('/\d/', $password)) {
        return "Hasło musi zawierać co najmniej jedną cyfrę.";
    }
    if (!preg_match('/[\W]/', $password)) {
        return "Hasło musi zawierać co najmniej jeden znak specjalny.";
    }
    return true;
}

function validate_birth_date($birth_date) {
    $birth_date = new DateTime($birth_date);
    $today = new DateTime();
    $age = $today->diff($birth_date)->y;
    return $age >= 18;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $password_confirm = htmlspecialchars($_POST['password_confirm']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $birth_date = $_POST['birth_date'];

    if ($password !== $password_confirm) {
        $error = "Hasła muszą się zgadzać.";
    } elseif (!validate_birth_date($birth_date)) {
        $error = "Musisz mieć co najmniej 18 lat.";
    } else {
        $password_validation = validate_password($password);
        if ($password_validation !== true) {
            $error = $password_validation;
        } else {
            $g = new GoogleAuthenticator();
            $secret = $g->generateSecret();

            $qrCodeUrl = GoogleQrUrl::generate($username, $secret, 'TwojaFirma');

            echo '<form id="redirectForm" method="post" action="verify.php">';
            echo '<input type="hidden" name="username" value="' . htmlspecialchars($username) . '">';
            echo '<input type="hidden" name="email" value="' . htmlspecialchars($email) . '">';
            echo '<input type="hidden" name="password" value="' . htmlspecialchars($password) . '">';
            echo '<input type="hidden" name="first_name" value="' . htmlspecialchars($first_name) . '">';
            echo '<input type="hidden" name="last_name" value="' . htmlspecialchars($last_name) . '">';
            echo '<input type="hidden" name="birth_date" value="' . htmlspecialchars($birth_date) . '">';
            echo '<input type="hidden" name="secret" value="' . htmlspecialchars($secret) . '">';
            echo '<input type="hidden" name="qrCodeUrl" value="' . htmlspecialchars($qrCodeUrl) . '">';
            echo '</form>';
            echo '<script>document.getElementById("redirectForm").submit();</script>';
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/validation.js" defer></script>
</head>
<body>
    <div class="container">
        <form method="post" id="registrationForm">
            <h2>Rejestracja</h2>
            <input type="text" name="username" placeholder="Nazwa użytkownika" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Hasło" required>
            <input type="password" name="password_confirm" placeholder="Potwierdź hasło" required>
            <input type="text" name="first_name" placeholder="Imię" required>
            <input type="text" name="last_name" placeholder="Nazwisko" required>
            <label for="birth_date">Data urodzenia:</label>
            <input type="date" name="birth_date" required>
            <button type="submit">Zarejestruj się</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
