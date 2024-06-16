<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['reset_password'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = htmlspecialchars($_POST['new_password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    // Sprawdzenie wymagań dotyczących hasła
    if (strlen($new_password) < 12 || 
        !preg_match('/[A-Z]/', $new_password) || 
        !preg_match('/[a-z]/', $new_password) || 
        !preg_match('/[0-9]/', $new_password) || 
        !preg_match('/[\W_]/', $new_password)) {
        $error = "Hasło musi mieć co najmniej 12 znaków, zawierać wielkie i małe litery, cyfry oraz znak specjalny.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Hasła nie są zgodne.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("UPDATE users SET password = ?, password_reset_required = 0 WHERE id = ?");
        $stmt->execute([$hashed_password, $_SESSION['user_id']]);

        unset($_SESSION['reset_password']);
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Resetowanie hasła</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function validatePassword() {
            var password = document.getElementById("new_password").value;
            var confirm_password = document.getElementById("confirm_password").value;
            var error = "";

            if (password.length < 12) {
                error = "Hasło musi mieć co najmniej 12 znaków.";
            } else if (!/[A-Z]/.test(password)) {
                error = "Hasło musi zawierać co najmniej jedną wielką literę.";
            } else if (!/[a-z]/.test(password)) {
                error = "Hasło musi zawierać co najmniej jedną małą literę.";
            } else if (!/[0-9]/.test(password)) {
                error = "Hasło musi zawierać co najmniej jedną cyfrę.";
            } else if (!/[\W_]/.test(password)) {
                error = "Hasło musi zawierać co najmniej jeden znak specjalny.";
            } else if (password !== confirm_password) {
                error = "Hasła nie są zgodne.";
            }

            document.getElementById("error_message").innerText = error;
            return error === "";
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Resetowanie hasła</h2>
        <form method="post" onsubmit="return validatePassword();">
            <label for="new_password">Nowe hasło:</label>
            <input type="password" id="new_password" name="new_password" required>
            <label for="confirm_password">Potwierdź nowe hasło:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <button type="submit">Ustaw nowe hasło</button>
            <p id="error_message" style="color: red;">
                <?php if (isset($error)): ?>
                    <?php echo $error; ?>
                <?php endif; ?>
            </p>
        </form>
    </div>
</body>
</html>
