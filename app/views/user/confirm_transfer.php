<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Potwierdzenie przelewu</title>
    <link rel="stylesheet" href="/2fatest/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="back-button-container">
                <a href="/2fatest/new_transfer" class="back-button">&lt;</a>
            </div>
            <h2>Potwierdzenie przelewu</h2>
            <div class="info">
                <label>Odbiorca:</label>
                <span><?php echo htmlspecialchars($recipient_name); ?></span>
            </div>
            <div class="info">
                <label>Numer konta:</label>
                <span><?php echo htmlspecialchars($recipient_account); ?></span>
            </div>
            <div class="info">
                <label>Tytuł przelewu:</label>
                <span><?php echo htmlspecialchars($transfer_title); ?></span>
            </div>
            <div class="info">
                <label>Kwota:</label>
                <span><?php echo number_format($amount, 2, ',', ' '); ?> zł</span>
            </div>
            <div class="info">
                <label>Data przelewu:</label>
                <span><?php echo htmlspecialchars($transfer_date); ?></span>
            </div>
            <form method="post" action="/confirm_transfer">
                <button type="submit">Potwierdź przelew</button>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
