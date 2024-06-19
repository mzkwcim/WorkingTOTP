<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Potwierdzenie przelewu</title>
    <link rel="stylesheet" href="/2fatest/public/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Potwierdzenie przelewu</h2>
        <form method="post" action="/confirm_transfer">
            <p>Odbiorca: <?php echo htmlspecialchars($recipient_name); ?></p>
            <p>Numer konta: <?php echo htmlspecialchars($recipient_account); ?></p>
            <p>Tytuł przelewu: <?php echo htmlspecialchars($transfer_title); ?></p>
            <p>Kwota: <?php echo number_format($amount, 2, ',', ' '); ?> zł</p>
            <p>Data przelewu: <?php echo htmlspecialchars($transfer_date); ?></p>
            <button type="submit">Potwierdź przelew</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
