<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Ustawienia</title>
    <link rel="stylesheet" href="/2fatest/public/css/styles.css">
    <style>
        .container {
            position: relative;
            padding: 40px 20px 20px 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
            margin-top: 50px;
            background-color: #f9f9f9;
        }

        .back-button-container {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }

        .back-button {
            text-decoration: none;
            font-size: 24px;
            color: #000;
        }

        .back-button:hover {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
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
</body>
</html>
