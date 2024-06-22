<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Weryfikacja</title>
    <link rel="stylesheet" href="/2fatest/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="back-button-container">
                <a href="/2fatest/register" class="back-button">&lt;</a>
            </div>
            <h2>Zeskanuj ten kod QR za pomocą Google Authenticator</h2>
            <div class="qr-code">
                <img src="<?php echo htmlspecialchars($data['qrCodeUrl']); ?>" alt="Kod QR">
            </div>
            <form method="post" action="/verify">
                <label for="totp_code">Kod TOTP</label>
                <input type="text" name="totp_code" placeholder="Wprowadź kod TOTP" required>
                <button type="submit">Zweryfikuj</button>
                <?php if (isset($error)): ?>
                    <p><?php echo $error; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
