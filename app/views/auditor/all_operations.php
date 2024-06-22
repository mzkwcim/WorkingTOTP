<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wszystkie operacje</title>
    <link rel="stylesheet" href="/2fatest/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="table-container">
        <div class="back-button-container">
                <a href="/2fatest/dashboard" class="back-button">&lt;</a>
            </div>
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
    </div>
</body>
</html>
