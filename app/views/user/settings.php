<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require '../db.php';

$user_id = $_SESSION['user_id'];

// Pobierz aktualne dane użytkownika i konto
$stmt = $pdo->prepare("
    SELECT u.username, ua.transaction_limit 
    FROM users u
    JOIN userAccount ua ON u.id = ua.user_id
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Zaktualizuj dane użytkownika i limity
    $new_username = $_POST['username'];
    $transaction_limit = $_POST['transaction_limit'];

    $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
    $stmt->execute([$new_username, $user_id]);

    $stmt = $pdo->prepare("UPDATE userAccount SET transaction_limit = ? WHERE user_id = ?");
    $stmt->execute([$transaction_limit, $user_id]);

    // Odśwież dane użytkownika
    $stmt = $pdo->prepare("
        SELECT u.username, ua.transaction_limit 
        FROM users u
        JOIN userAccount ua ON u.id = ua.user_id
        WHERE u.id = ?
    ");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    $success = "Dane zostały zaktualizowane.";
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Ustawienia</title>
    <link rel="stylesheet" href="css/styles.css">
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
            <a href="/2fatest/public/dashboard" class="back-button">&lt;</a>
        </div>
        <h2>Ustawienia</h2>
        <form method="post">
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            
            <label for="transaction_limit">Limit jednorazowej transakcji (zł):</label>
            <input type="number" step="0.01" name="transaction_limit" value="<?php echo htmlspecialchars($user['transaction_limit']); ?>" required>
            
            <button type="submit">Zapisz zmiany</button>
            <?php if (isset($success)): ?>
                <p><?php echo $success; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
