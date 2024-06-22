<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="/2fatest/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="back-button-container">
                <a href="/2fatest/" class="back-button">&lt;</a>
            </div>
            <h2>Logowanie</h2>
            <?php if (isset($message)): ?>
                <p class="message"><?php echo $message; ?></p>
            <?php endif; ?>
            <form method="post" action="/2fatest/login">
                <input type="text" name="username" placeholder="Nazwa użytkownika" required>
                <input type="password" name="password" placeholder="Hasło" required>
                <button type="submit">Zaloguj się</button>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
