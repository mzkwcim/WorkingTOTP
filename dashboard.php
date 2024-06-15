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
            width: 95%;
            margin: 0 auto;
            max-width: 1200px;
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

        .transaction-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }

        .transaction-row:hover {
            background-color: #f9f9f9;
        }

        .transaction-name {
            flex: 1;
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .transaction-amount {
            flex: 0;
            width: 150px;
            text-align: right;
            white-space: nowrap;
        }

        .transaction-outgoing {
            color: red;
        }

        .transaction-incoming {
            color: green;
        }

        .transaction-details {
            display: none;
            padding: 8px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            margin-bottom: 10px;
        }

        .transaction-details p {
            margin: 0;
            padding: 4px 0;
            color: black;
            text-align: left;
        }

        .transaction-details p strong {
            color: black;
        }
    </style>
    <script>
        function toggleDetails(transactionId) {
            const details = document.getElementById('details-' + transactionId);
            details.style.display = details.style.display === 'none' ? 'block' : 'none';
        }
    </script>
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
        <div class="transactions-table">
            <?php foreach ($transactions as $transaction): ?>
                <div class="transaction-row" onclick="toggleDetails(<?php echo $transaction['id']; ?>)">
                    <div class="transaction-name">
                        <?php 
                        echo $transaction['transfer_type'] == 'outgoing' ? $transaction['recipient_name'] : $transaction['sender_name']; 
                        ?>
                    </div>
                    <div class="transaction-amount <?php echo $transaction['transfer_type'] == 'outgoing' ? 'transaction-outgoing' : 'transaction-incoming'; ?>">
                        <?php echo $transaction['transfer_type'] == 'outgoing' ? '-' : '+'; ?>
                        <?php echo number_format($transaction['amount'], 2, ',', ' '); ?> zł
                    </div>
                </div>
                <div id="details-<?php echo $transaction['id']; ?>" class="transaction-details">
                    <p><strong>Kwota:</strong> <?php echo number_format($transaction['amount'], 2, ',', ' '); ?> zł</p>
                    <p><strong><?php echo $transaction['transfer_type'] == 'outgoing' ? 'Odbiorca' : 'Nadawca'; ?>:</strong> 
                        <?php 
                        echo $transaction['transfer_type'] == 'outgoing' ? $transaction['recipient_name'] : $transaction['sender_name']; 
                        ?>
                    </p>
                    <p><strong>Data transakcji:</strong> <?php echo $transaction['transfer_date']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>