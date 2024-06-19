<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Potwierdzenie hasła</title>
    <link rel="stylesheet" href="/2fatest/public/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Potwierdzenie hasła</h2>
        <form method="post" action="/confirm_password">
            <input type="hidden" name="recipient_name" value="<?php echo htmlspecialchars($_SESSION['recipient_name']); ?>">
            <input type="hidden" name="recipient_account" value="<?php echo htmlspecialchars($_SESSION['recipient_account']); ?>">
            <input type="hidden" name="transfer_title" value="<?php echo htmlspecialchars($_SESSION['transfer_title']); ?>">
            <input type="hidden" name="amount" value="<?php echo htmlspecialchars($_SESSION['amount']); ?>">
            <input type="hidden" name="transfer_date" value="<?php echo htmlspecialchars($_SESSION['transfer_date']); ?>">
            <label for="password">Hasło:</label>
            <input type="password" name="password" required>
            <button type="submit">Potwierdź</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
