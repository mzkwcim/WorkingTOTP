<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Ustawienia</title>
    <link rel="stylesheet" href="/2fatest/css/styles.css">
</head>
<body>
    <div class="container">
    <div class="form-container">
        <div class="back-button-container">
            <a href="/2fatest/dashboard" class="back-button">&lt;</a>
        </div>
        <h2>Ustawienia</h2>
        <form method="post">
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            
            <label for="transaction_limit">Limit jednorazowej transakcji (zł):</label>
            <input type="number" step="0.01" name="transaction_limit" value="<?php echo htmlspecialchars($transaction_limit); ?>" required>
            
            <button type="submit">Zapisz zmiany</button>
            <?php if (isset($success)): ?>
                <p><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
        </form>
    </div>
    </div>
</body>
</html>
