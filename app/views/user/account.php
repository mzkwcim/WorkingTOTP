<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require '../db.php';

$user_id = $_SESSION['user_id'];

// Pobierz informacje o użytkowniku
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM userAccount WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_account = $stmt->fetch();

$full_name = $user['first_name'] . ' ' . $user['last_name'];
$email = $user['email'];
$account_number = $user_account['account_number'];
$role = $user['role']; // Pobranie roli użytkownika
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Informacje o koncie</title>
    <link rel="stylesheet" href="css/styles.css">
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
        <a href="/2fatest/public/dashboard" class="back-button">&lt;</a>
        <h2>Informacje o koncie</h2>
        <div class="info">
            <label>Numer konta:</label>
            <span><?php echo $account_number; ?></span>
        </div>
        <div class="info">
            <label>Imię i nazwisko:</label>
            <span><?php echo $full_name; ?></span>
        </div>
        <div class="info">
            <label>Email:</label>
            <span><?php echo $email; ?></span>
        </div>
        <div class="info">
            <label>Rola:</label>
            <span><?php echo $role; ?></span>
        </div>
        <button class="mysterious-button" onclick="mysteriousFunction()">Czy Pies</button>
    </div>
</body>
</html>
