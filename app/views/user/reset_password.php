<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Resetowanie hasła</title>
    <link rel="stylesheet" href="/2fatest/public/css/styles.css">
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Resetowanie hasła</h2>
        <form method="post" action="/2fatest/reset_password_action">
            <label for="new_password">Nowe hasło:</label>
            <input type="password" name="new_password" required>
            <label for="confirm_password">Potwierdź nowe hasło:</label>
            <input type="password" name="confirm_password" required>
            <button type="submit">Resetuj hasło</button>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
