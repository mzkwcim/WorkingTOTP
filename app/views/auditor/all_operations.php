<?php


require '../db.php';

// Pobierz wszystkie transakcje
$stmt = $pdo->query("SELECT * FROM transfers ORDER BY transfer_date DESC");
$transfers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wszystkie operacje</title>
    <link rel="stylesheet" href="/2fatest/public/css/styles.css">
    <style>
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            overflow-x: auto; /* Dodano przewijanie poziome */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
        }

        .back-button {
            display: inline-block;
            margin-bottom: 10px;
            padding: 10px 20px;
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
    </style>
</head>
<body>
    <div class="container">
        <a href="/2fatest/public/dashboard" class="back-button">&lt; Wróć</a>
        <h2>Wszystkie operacje</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nadawca</th>
                    <th>Odbiorca</th>
                    <th>Kwota</th>
                    <th>Tytuł przelewu</th>
                    <th>Data przelewu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transfers as $transfer): ?>
                <tr>
                    <td><?php echo htmlspecialchars($transfer['id']); ?></td>
                    <td><?php echo htmlspecialchars($transfer['sender_name']); ?></td>
                    <td><?php echo htmlspecialchars($transfer['recipient_name']); ?></td>
                    <td><?php echo htmlspecialchars($transfer['amount']); ?></td>
                    <td><?php echo htmlspecialchars($transfer['transfer_title']); ?></td>
                    <td><?php echo htmlspecialchars($transfer['transfer_date']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
