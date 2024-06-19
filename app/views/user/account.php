<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Informacje o koncie</title>
    <link rel="stylesheet" href="/2fatest/public/css/styles.css">
    <style>
        .container {
            position: relative;
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .back-button {
            display: inline-block;
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-decoration: none;
            color: #000;
            font-weight: bold;
        }

        .back-button:hover {
            background-color: #e0e0e0;
        }

        .info {
            margin: 20px 0;
        }

        .info label {
            display: block;
            font-weight: bold;
        }

        .info span {
            display: block;
            margin-bottom: 10px;
        }

        .mysterious-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: transparent;
            border: none;
            cursor: pointer;
            color: transparent;
        }

        .mysterious-button:hover {
            color: transparent;
            background-color: transparent;
        }
    </style>
    <script>
        function mysteriousFunction() {
            alert("CzyPies");
        }
    </script>
</head>
<body>
    <div class="container">
        <a href="/2fatest/dashboard" class="back-button">&lt;</a>
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
</body>
</html>
