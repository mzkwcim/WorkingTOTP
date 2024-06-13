<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;

        if ($user['totp_verified'] == 0) {
            echo '<form id="redirectForm" method="post" action="verify.php">';
            echo '<input type="hidden" name="secret" value="' . $user['secret'] . '">';
            echo '<input type="hidden" name="username" value="' . htmlspecialchars($username) . '">';
            echo '<input type="hidden" name="password" value="' . htmlspecialchars($password) . '">';
            echo '</form>';
            echo '<script>document.getElementById("redirectForm").submit();</script>';
            exit();
        } else {
            header("Location: dashboard.php");
            exit();
        }
    } else {
        $error = "Nieprawidłowe dane logowania";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <form method="post">
            <h2>Logowanie</h2>
            <input type="text" name="username" placeholder="Nazwa użytkownika" required>
            <input type="password" name="password" placeholder="Hasło" required>
            <button type="submit">Zaloguj się</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
