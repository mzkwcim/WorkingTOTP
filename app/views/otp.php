<?php
require 'db.php';
require 'vendor/autoload.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $code = $_POST['code'];

    $stmt = $pdo->prepare("SELECT secret FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user) {
        $g = new GoogleAuthenticator();
        if ($g->checkCode($user['secret'], $code)) {
            echo "Weryfikacja pomyślna! Jesteś zalogowany.";
            // Tutaj możesz przekierować użytkownika do jego profilu lub strony głównej
            exit();
        } else {
            $error = "Nieprawidłowy kod";
        }
    } else {
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Weryfikacja OTP</title>
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
        .form-container h1 {
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
</head>
<body>
    <div class="container">
        <a href="/2fatest/public/" class="back-button">&lt; Wróć</a>
        <div class="form-container">
            <h1>Weryfikacja OTP</h1>
            <div>
                <img src="<?php echo $qrCodeUrl; ?>" alt="Kod QR">
            </div>
            <form method="post">
                <label for="totp_code">Kod TOTP:</label>
                <input type="text" id="totp_code" name="totp_code" required>
                <br>
                <button type="submit">Zweryfikuj</button>
                <?php if (isset($error)): ?>
                    <p><?php echo $error; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>


