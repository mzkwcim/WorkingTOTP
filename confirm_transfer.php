<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['recipient_name'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db.php';
    $user_id = $_SESSION['user_id'];
    $recipient_name = $_SESSION['recipient_name'];
    $recipient_account = $_SESSION['recipient_account'];
    $transfer_title = $_SESSION['transfer_title'];
    $amount = $_SESSION['amount'];
    $transfer_date = $_SESSION['transfer_date'];

    // Pobierz informacje o nadawcy
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT * FROM userAccount WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_account = $stmt->fetch();

    $sender_name = $user['first_name'] . ' ' . $user['last_name'];
    $sender_account = $user_account['account_number'];

    // Sprawdź, czy numer konta odbiorcy istnieje w bazie danych
    $stmt = $pdo->prepare("SELECT * FROM userAccount WHERE account_number = ?");
    $stmt->execute([$recipient_account]);
    $account_exists = $stmt->fetch();

    if (!$account_exists) {
        $error = "Numer konta odbiorcy nie istnieje.";
    } else {
        // Rozpocznij transakcję
        $pdo->beginTransaction();

        try {
            // Dodaj przelew do tabeli transfers
            $stmt = $pdo->prepare("INSERT INTO transfers (user_id, sender_name, sender_account, recipient_name, recipient_account, transfer_title, amount, transfer_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $sender_name, $sender_account, $recipient_name, $recipient_account, $transfer_title, $amount, $transfer_date]);

            // Zaktualizuj saldo nadawcy (odejmij kwotę)
            $stmt = $pdo->prepare("UPDATE userAccount SET balance = balance - ? WHERE account_number = ?");
            $stmt->execute([$amount, $sender_account]);

            // Zaktualizuj saldo odbiorcy (dodaj kwotę)
            $stmt = $pdo->prepare("UPDATE userAccount SET balance = balance + ? WHERE account_number = ?");
            $stmt->execute([$amount, $recipient_account]);

            // Zatwierdź transakcję
            $pdo->commit();
            if ($amount == 69){
                header("Location: zboczuch.php");
            } elseif($amount == 420){
                header("Location: parararara.php");
            } elseif($amount = 2137){
                header("Location: papaj.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } catch (Exception $e) {
            // Wycofaj transakcję w razie błędu
            $pdo->rollBack();
            $error = "Wystąpił błąd podczas wysyłania przelewu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Potwierdzenie przelewu</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Potwierdzenie przelewu</h2>
        <form method="post">
            <p>Odbiorca: <?php echo htmlspecialchars($_SESSION['recipient_name']); ?></p>
            <p>Numer konta: <?php echo htmlspecialchars($_SESSION['recipient_account']); ?></p>
            <p>Tytuł przelewu: <?php echo htmlspecialchars($_SESSION['transfer_title']); ?></p>
            <p>Kwota: <?php echo number_format($_SESSION['amount'], 2, ',', ' '); ?> zł</p>
            <p>Data przelewu: <?php echo htmlspecialchars($_SESSION['transfer_date']); ?></p>
            <button type="submit">Potwierdź przelew</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
