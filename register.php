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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        $error = "Hasła muszą się zgadzać.";
    } else {
        $password_validation = validate_password($password);
        if ($password_validation !== true) {
            $error = $password_validation;
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Sprawdzenie czy email jest unikalny
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $error = "Email jest już zajęty.";
            } else {
                $g = new GoogleAuthenticator();
                $secret = $g->generateSecret();

                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, secret) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password, $secret]);

                echo '<form id="redirectForm" method="post" action="verify.php">';
                echo '<input type="hidden" name="secret" value="' . $secret . '">';
                echo '<input type="hidden" name="username" value="' . htmlspecialchars($username) . '">';
                echo '<input type="hidden" name="password" value="' . htmlspecialchars($password) . '">';
                echo '</form>';
                echo '<script>document.getElementById("redirectForm").submit();</script>';
                exit();
            }
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
    <form method="post">
        
        <div class="container">
            <h2>Rejestracja</h2>
            <input type="text" name="username" placeholder="Nazwa użytkownika" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Hasło" required>
            <input type="password" name="password_confirm" placeholder="Potwierdź hasło" required>
            <button type="submit">Zarejestruj się</button>
        </div>
        <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
