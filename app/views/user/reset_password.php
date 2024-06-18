<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['reset_password'])) {
    header("Location: /2fatest/public/login");
    exit();
}

require '../db.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = htmlspecialchars($_POST['new_password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        $error = "Hasła muszą się zgadzać.";
    } elseif (strlen($new_password) < 12 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[a-z]/', $new_password) || !preg_match('/\d/', $new_password) || !preg_match('/[\W]/', $new_password)) {
        $error = "Hasło musi mieć co najmniej 12 znaków, zawierać wielkie i małe litery, cyfrę i znak specjalny.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, password_reset_required = 0 WHERE id = ?");
        $stmt->execute([$hashed_password, $user_id]);
        unset($_SESSION['reset_password']);
        header("Location: /2fatest/public/login");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zresetuj hasło</title>
    <link rel="stylesheet" href="/2fatest/public/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Zresetuj hasło</h2>
        <form method="post">
            <label for="new_password">Nowe hasło:</label>
            <input type="password" name="new_password" required>
            <label for="confirm_password">Potwierdź hasło:</label>
            <input type="password" name="confirm_password" required>
            <button type="submit">Zresetuj hasło</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
