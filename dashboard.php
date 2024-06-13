<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$transactions = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'], $_POST['amount'], $_POST['type'])) {
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, date, amount, type) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $date, $amount, $type]);
}

$stmt = $pdo->prepare("SELECT date, amount, type FROM transactions WHERE user_id = ?");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Dashboard z Transakcjami</h2>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Kwota</th>
                    <th>Rodzaj</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?php echo htmlspecialchars($transaction['date']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['amount']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['type']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form method="post">
            <h3>Dodaj Nową Transakcję</h3>
            <label for="date">Data:</label>
            <input type="date" name="date" required>
            <label for="amount">Kwota:</label>
            <input type="number" name="amount" required>
            <label for="type">Rodzaj:</label>
            <select name="type" required>
                <option value="Wpływy">Wpływy</option>
                <option value="Wydatki">Wydatki</option>
            </select>
            <button type="submit">Dodaj Transakcję</button>
        </form>
    </div>
</body>
</html>
