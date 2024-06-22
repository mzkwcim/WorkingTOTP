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
            </form>
        </div>
    </div>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="successMessage" class="message"><?php echo $success ?? ''; ?></p>
        </div>
    </div>

    <script src="/2fatest/js/script.js"></script>
    <?php if (isset($success) && !empty($success)): ?>
        <script>
            initializeModal();
        </script>
    <?php endif; ?>
</body>
</html>
