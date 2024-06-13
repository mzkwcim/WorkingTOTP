<?php
session_start();
require 'db.php';
require 'vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleQrUrl;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['secret'])) {
        $secret = $_POST['secret'];
        $_SESSION['secret'] = $secret;
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];
        $qrCodeUrl = GoogleQrUrl::generate('TwojaAplikacja', $secret, 'TwojaFirma');
    } elseif (isset($_POST['totp_code'])) {
        $totp_code = $_POST['totp_code'];
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];
        $secret = $_SESSION['secret'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            $g = new GoogleAuthenticator();
            if ($g->checkCode($user['secret'], $totp_code)) {
                $stmt = $pdo->prepare("UPDATE users SET totp_verified = 1 WHERE id = ?");
                $stmt->execute([$user['id']]);

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Nieprawidłowy kod TOTP";
            }
        } else {
            header("Location: login.php");
            exit();
        }
    } elseif (isset($_POST['delete'])) {
        // Usunięcie użytkownika z bazy danych na podstawie secret
        $secret = $_POST['delete'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE secret = ?");
        $stmt->execute([$secret]);

        header("Location: register.php");
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Weryfikacja</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Zeskanuj ten kod QR za pomocą Google Authenticator</h2>
        <div class="qr-code">
            <img src="<?php echo $qrCodeUrl; ?>" alt="Kod QR">
        </div>
        <form method="post">
            <label for="totp_code">Kod TOTP</label>
            <input type="text" name="totp_code" placeholder="Wprowadź kod TOTP" required>
            <button type="submit">Zweryfikuj</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
        <div class="button-container">
            <form method="post">
                <input type="hidden" name="delete" value="<?php echo $_SESSION['secret']; ?>">
                <button type="submit">Wróć</button>
            </form>
            <form method="post" action="login.php">
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>">
                <input type="hidden" name="password" value="<?php echo htmlspecialchars($_SESSION['password']); ?>">
                <button type="submit">Przejdź do logowania</button>
            </form>
        </div>
    </div>
</body>
</html>
