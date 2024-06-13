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
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <form method="post">
        <h2>Wprowadź kod z Google Authenticator</h2>
        <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>">
        <input type="text" name="code" placeholder="Kod OTP" required>
        <button type="submit">Weryfikuj</button>
        <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
