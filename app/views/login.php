<?php
session_start();
require '../db.php'; // Upewnij się, że ścieżka do pliku db.php jest poprawna

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['password_reset_required']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['reset_password'] = true;
            header("Location: /2fatest/public/reset_password.php");
            exit();
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['secret'] = $user['secret'];
            header("Location: /2fatest/public/verify_login_totp");
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
</head>
<body>
    <div class="container">
        <a href="/2fatest/public/" class="back-button">&lt; Wróć</a>
        <div class="form-container">
            <h2>Logowanie</h2>
            <form method="post">
                <input type="text" name="username" placeholder="Nazwa użytkownika" required>
                <input type="password" name="password" placeholder="Hasło" required>
                <button type="submit">Zaloguj się</button>
                <?php if (isset($error)): ?>
                    <p><?php echo $error; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>






