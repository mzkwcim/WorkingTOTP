<?php
require '../db.php';
require '../vendor/autoload.php';

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

            echo '<form id="redirectForm" method="post" action="/2fatest/app/views/verify.php">';
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
    <link rel="stylesheet" href="/2fatest/public/css/styles.css">
    <style>
        .back-button {
            display: inline-block;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-decoration: none;
            color: #000;
            font-weight: bold;
            margin-bottom: 10px;
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10; /* Ensure button is above other elements */
        }
        .back-button:hover {
            background-color: #e0e0e0;
        }
        .form-container {
            text-align: center;
            position: relative;
        }
        .form-container h2 {
            text-align: center;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            position: relative;
        }
    </style>
    <script>
        function validateForm() {
            var password = document.forms["registerForm"]["password"].value;
            var confirmPassword = document.forms["registerForm"]["password_confirm"].value;
            if (password !== confirmPassword) {
                alert("Hasła nie są zgodne.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <a href="/2fatest/public/" class="back-button">&lt; Wróć</a>
        <div class="form-container">
            <h2>Rejestracja</h2>
            <form name="registerForm" method="post" onsubmit="return validateForm()">
                <input type="text" name="username" placeholder="Nazwa użytkownika" required>
                <input type="password" name="password" placeholder="Hasło" required>
                <input type="password" name="password_confirm" placeholder="Potwierdź hasło" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="first_name" placeholder="Imię" required>
                <input type="text" name="last_name" placeholder="Nazwisko" required>
                <input type="date" name="birth_date" placeholder="Data urodzenia" required>
                <button type="submit">Zarejestruj się</button>
                <?php if (isset($error)): ?>
                    <p><?php echo $error; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
