<?php
session_start();
require 'db.php';
require 'vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['totp_code'])) {
    $totp_code = $_POST['totp_code'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if ($user) {
        $g = new GoogleAuthenticator();
        if ($g->checkCode($user['secret'], $totp_code)) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Nieprawidłowy kod TOTP";
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
    <title>Weryfikacja TOTP</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Wprowadź kod TOTP</h2>
        <form method="post">
            <label for="totp_code">Kod TOTP</label>
            <input type="text" name="totp_code" placeholder="Wprowadź kod TOTP" required>
            <button type="submit">Zweryfikuj</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
