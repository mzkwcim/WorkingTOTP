<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/2fatest/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".transaction-row").click(function() {
                $(this).next(".transaction-details").slideToggle();
            });
        });
    </script>
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
            <h3>Historia transakcji</h3>
            <div class="transactions-table">
                <?php foreach ($transactions as $transaction): ?>
                    <div class="transaction-row">
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
                    <div class="transaction-details" style="display: none;">
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
    </div>
</body>
</html>
