<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/2fatest/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/2fatest/js/toggle.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Dashboard</h2>
            <div class="balance">
                <label>Saldo konta:</label>
                <span><?php echo number_format($balance, 2, ',', ' '); ?> zł</span>
            </div>
            <div class="button-container">
                <form action="/2fatest/new_transfer" method="get">
                    <button type="submit">Nowy przelew</button>
                </form>
                <form action="/2fatest/account" method="get">
                    <button type="submit">Konto</button>
                </form>
                <form action="/2fatest/settings" method="get">
                    <button type="submit">Ustawienia</button>
                </form>
                <?php if ($role == 'auditor'): ?>
                    <form action="/2fatest/all_operations" method="get">
                        <button type="submit">Wszystkie operacje</button>
                    </form>
                <?php elseif ($role == 'admin'): ?>
                    <form action="/2fatest/manage_users" method="get">
                        <button type="submit">Zarządzaj użytkownikami</button>
                    </form>
                <?php endif; ?>
                <form action="/2fatest/logout" method="get">
                    <button type="submit">Wyloguj się</button>
                </form>
            </div>
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
                <div id="details-<?php echo $transaction['id']; ?>" class="transaction-details" style="display: none;">
                    <div class="detail-row">
                        <span class="detail-label">Kwota:</span>
                        <span class="detail-data"><?php echo number_format($transaction['amount'], 2, ',', ' '); ?> zł</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><?php echo $transaction['transfer_type'] == 'outgoing' ? 'Odbiorca' : 'Nadawca'; ?>:</span>
                        <span class="detail-data"><?php echo $transaction['transfer_type'] == 'outgoing' ? $transaction['recipient_name'] : $transaction['sender_name']; ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Numer konta odbiorcy:</span>
                        <span class="detail-data"><?php echo htmlspecialchars($transaction['recipient_account']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Numer konta nadawcy:</span>
                        <span class="detail-data"><?php echo htmlspecialchars($transaction['sender_account']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Data transakcji:</span>
                        <span class="detail-data"><?php echo htmlspecialchars($transaction['transfer_date']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
