<?php
session_start();
require '../db.php'; // Upewnij się, że ścieżka do pliku db.php jest poprawna
require '../vendor/autoload.php'; // Upewnij się, że ścieżka do pliku autoload.php jest poprawna

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['totp_code'])) {
        $error = "Proszę wprowadzić kod TOTP";
    } else {
        $totp_code = $_POST['totp_code'];
        $user_id = $_SESSION['user_id'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user) {
            $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
            if ($g->checkCode($user['secret'], $totp_code)) {
                // TOTP zweryfikowane, przekierowanie do dashboard
                header("Location: /2fatest/public/dashboard");
                exit();
            } else {
                $error = "Nieprawidłowy kod TOTP";
            }
        } else {
            header("Location: /2fatest/public/login");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Weryfikacja TOTP</title>
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
            <h1>Weryfikacja TOTP</h1>
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


