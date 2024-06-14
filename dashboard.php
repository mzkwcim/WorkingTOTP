<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];

// Pobierz historię transakcji użytkownika (zarówno wysłane, jak i otrzymane)
$stmt = $pdo->prepare("SELECT t.*, 
    (CASE WHEN t.user_id = :user_id THEN 'outgoing' ELSE 'incoming' END) AS transfer_type
    FROM transfers t 
    LEFT JOIN userAccount ua ON t.recipient_account = ua.account_number 
    WHERE t.user_id = :user_id OR ua.user_id = :user_id
    ORDER BY t.transfer_date DESC");
$stmt->execute(['user_id' => $user_id]);
$transactions = $stmt->fetchAll();

// Pobierz saldo konta użytkownika
$stmt = $pdo->prepare("SELECT balance FROM userAccount WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_account = $stmt->fetch();
$balance = $user_account['balance'];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .container {
            width: 100%;
            margin: 0 auto;
            max-width: 1200px; /* Set a maximum width */
        }

        .balance {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .button-container form {
            margin: 0;
        }

        .button-container button {
            margin-right: 10px;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .transactions-table th, .transactions-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            white-space: nowrap; /* Ensure that content does not wrap */
        }

        .transactions-table th {
            background-color: #f2f2f2;
        }

        .transactions-table td:nth-child(1) {
            width: 15%;
        }

        .transactions-table td:nth-child(2) {
            width: 20%;
        }

        .transactions-table td:nth-child(3) {
            width: 20%;
        }

        .transactions-table td:nth-child(4) {
            width: 30%;
        }

        .transactions-table td:nth-child(5) {
            width: 15%;
        }

        .transaction-outgoing {
            color: red;
        }

        .transaction-incoming {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard</h2>
        <div class="balance">Saldo konta: <?php echo number_format($balance, 2, ',', ' '); ?> zł</div>
        <div class="button-container">
            <form action="new_transfer.php">
                <button type="submit">Nowy przelew</button>
            </form>
            <form action="account.php">
                <button type="submit">Konto</button>
            </form>
            <form action="settings.php">
                <button type="submit">Ustawienia</button>
            </form>
            <form action="logout.php">
                <button type="submit">Wyloguj się</button>
            </form>
        </div>
        <h3>Historia transakcji</h3>
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Nadawca</th>
                    <th>Odbiorca</th>
                    <th>Tytuł</th>
                    <th>Kwota</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?php echo $transaction['transfer_date']; ?></td>
                        <td><?php echo $transaction['sender_name']; ?></td>
                        <td><?php echo $transaction['recipient_name']; ?></td>
                        <td><?php echo $transaction['transfer_title']; ?></td>
                        <td class="<?php echo $transaction['transfer_type'] == 'outgoing' ? 'transaction-outgoing' : 'transaction-incoming'; ?>">
                            <?php echo $transaction['transfer_type'] == 'outgoing' ? '-' : ''; ?>
                            <?php echo number_format($transaction['amount'], 2, ',', ' '); ?> zł
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
