<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="/2fatest/public/css/styles.css">
</head>
<body>
    <div class="container">
        <a href="/2fatest/" class="back-button">&lt;</a>
        <div class="form-container">
            <h2>Logowanie</h2>
            <form method="post" action="/login">
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
