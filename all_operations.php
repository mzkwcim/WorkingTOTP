<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require 'db.php';

// Pobierz wszystkie operacje
$stmt = $pdo->prepare("SELECT * FROM transfers ORDER BY transfer_date DESC");
$stmt->execute();
$all_transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wszystkie operacje</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .container {
            position: relative;
            width: 95%;
            margin: 0 auto;
            max-width: 1200px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
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
        <div class="back-button-container">
            <a href="dashboard.php" class="back-button">&lt;</a>
        </div>
        <h2>Wszystkie operacje</h2>
        <div class="transactions-table">
            <?php foreach ($all_transactions as $transaction): ?>
                <div class="transaction-row" onclick="toggleDetails(<?php echo $transaction['id']; ?>)">
                    <div class="transaction-name">
                        <?php echo htmlspecialchars($transaction['sender_name']); ?>
                    </div>
                    <div class="transaction-name">
                        <?php echo htmlspecialchars($transaction['recipient_name']); ?>
                    </div>
                    <div class="transaction-amount">
                        <?php echo number_format($transaction['amount'], 2, ',', ' '); ?> zł
                    </div>
                </div>
                <div id="details-<?php echo $transaction['id']; ?>" class="transaction-details">
                    <p><strong>ID transakcji:</strong> <?php echo htmlspecialchars($transaction['id']); ?></p>
                    <p><strong>Nadawca:</strong> <?php echo htmlspecialchars($transaction['sender_name']); ?></p>
                    <p><strong>Odbiorca:</strong> <?php echo htmlspecialchars($transaction['recipient_name']); ?></p>
                    <p><strong>Numer konta nadawcy:</strong> <?php echo htmlspecialchars($transaction['sender_account']); ?></p>
                    <p><strong>Numer konta odbiorcy:</strong> <?php echo htmlspecialchars($transaction['recipient_account']); ?></p>
                    <p><strong>Kwota:</strong> <?php echo number_format($transaction['amount'], 2, ',', ' '); ?> zł</p>
                    <p><strong>Tytuł przelewu:</strong> <?php echo htmlspecialchars($transaction['transfer_title']); ?></p>
                    <p><strong>Data transakcji:</strong> <?php echo htmlspecialchars($transaction['transfer_date']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
