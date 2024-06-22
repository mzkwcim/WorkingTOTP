<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Weryfikacja TOTP</title>
    <link rel="stylesheet" href="/2fatest/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="back-button-container">
                <a href="/2fatest/login" class="back-button">&lt;</a>
            </div>
            <h1>Weryfikacja TOTP</h1>
            <form method="post" action="/verify_login_totp">
                <label for="totp_code">Kod TOTP:</label>
                <input type="text" id="totp_code" name="totp_code" required>
                <br>
                <button type="submit">Zweryfikuj</button>
                <?php if (isset($error)): ?>
                    <p><?php echo $error; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
