<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Informacje o koncie</title>
    <link rel="stylesheet" href="/2fatest/css/styles.css">
    <script>
        function mysteriousFunction() {
            alert("CzyPies");
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="back-button-container">
                <a href="/2fatest/dashboard" class="back-button">&lt;</a>
            </div>
            <h2>Informacje o koncie</h2>
            <div class="info">
                <label>Numer konta:</label>
                <span><?php echo htmlspecialchars($account_number); ?></span>
            </div>
            <div class="info">
                <label>Imię i nazwisko:</label>
                <span><?php echo htmlspecialchars($full_name); ?></span>
            </div>
            <div class="info">
                <label>Email:</label>
                <span><?php echo htmlspecialchars($email); ?></span>
            </div>
            <div class="info">
                <label>Rola:</label>
                <span><?php echo htmlspecialchars($role); ?></span>
            </div>
            <button class="mysterious-button" onclick="mysteriousFunction()">Czy Pies</button>
        </div>
    </div>
</body>
</html>
