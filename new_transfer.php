<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];

// Pobierz informacje o nadawcy
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM userAccount WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_account = $stmt->fetch();

$sender_name = $user['first_name'] . ' ' . $user['last_name'];
$sender_account = $user_account['account_number'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_name = htmlspecialchars($_POST['recipient_name']);
    $recipient_account = htmlspecialchars($_POST['recipient_account']);
    $transfer_title = htmlspecialchars($_POST['transfer_title']);
    $amount = htmlspecialchars($_POST['amount']);
    $transfer_date = $_POST['transfer_date'];

    if ($amount <= 0) {
        $error = "Kwota musi być większa niż zero.";
    } elseif (!preg_match('/^PL\d{26}$/', $recipient_account)) {
        $error = "Numer konta musi zaczynać się od 'PL' i mieć 26 cyfr.";
    } else {
        // Sprawdź, czy numer konta odbiorcy istnieje w bazie danych
        $stmt = $pdo->prepare("SELECT * FROM userAccount WHERE account_number = ?");
        $stmt->execute([$recipient_account]);
        $account_exists = $stmt->fetch();

        if (!$account_exists) {
            $error = "Numer konta odbiorcy nie istnieje.";
        } else {
            // Sprawdź, czy data przelewu to dzisiaj
            $today = date('Y-m-d');
            if ($transfer_date === $today) {
                try {
                    // Rozpocznij transakcję
                    $pdo->beginTransaction();

                    // Dodaj przelew do tabeli transfers
                    $stmt = $pdo->prepare("INSERT INTO transfers (user_id, sender_name, sender_account, recipient_name, recipient_account, transfer_title, amount, transfer_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$user_id, $sender_name, $sender_account, $recipient_name, $recipient_account, $transfer_title, $amount, $transfer_date]);

                    // Zaktualizuj saldo nadawcy (odejmij kwotę)
                    $stmt = $pdo->prepare("UPDATE userAccount SET balance = balance - ? WHERE account_number = ?");
                    $stmt->execute([$amount, $sender_account]);

                    // Zaktualizuj saldo odbiorcy
                    $stmt = $pdo->prepare("UPDATE userAccount SET balance = balance + ? WHERE account_number = ?");
                    $stmt->execute([$amount, $recipient_account]);

                    // Zatwierdź transakcję
                    $pdo->commit();

                    if ($amount == 69) {
                        header("Location: Zboczuch.php");
                    } elseif ($amount == 420){
                        header("Location: parararara.php");
                    } elseif ($amount == 2137) {
                        header("Location: papaj.php");
                    } else {
                        echo '<script>alert("Przelew został pomyślnie wysłany."); window.location.href = "dashboard.php";</script>';
                    }
                    exit();
                } catch (Exception $e) {
                    // Wycofaj transakcję w razie błędu
                    $pdo->rollBack();
                    $error = "Wystąpił błąd podczas wysyłania przelewu.";
                }
            } else {
                try {
                    // Dodaj przelew do tabeli pending_transfers
                    $stmt = $pdo->prepare("INSERT INTO pending_transfers (user_id, sender_name, sender_account, recipient_name, recipient_account, transfer_title, amount, transfer_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$user_id, $sender_name, $sender_account, $recipient_name, $recipient_account, $transfer_title, $amount, $transfer_date]);

                    echo '<script>alert("Przelew został pomyślnie zaplanowany."); window.location.href = "dashboard.php";</script>';
                    exit();
                } catch (Exception $e) {
                    $error = "Wystąpił błąd podczas planowania przelewu.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Nowy przelew</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var today = new Date().toISOString().split('T')[0];
            document.getElementsByName("transfer_date")[0].setAttribute('min', today);
        });
    </script>
    <style>
        .container {
            position: relative;
        }

        .back-button {
            display: inline-block;
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 10px;
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
        <a href="dashboard.php" class="back-button">&lt;</a>
        <h2>Nowy przelew</h2>
        <form method="post">
            <label for="recipient_name">Odbiorca:</label>
            <input type="text" name="recipient_name" placeholder="Imię i nazwisko odbiorcy" required>
            <label for="recipient_account">Numer konta:</label>
            <input type="text" name="recipient_account" placeholder="Numer konta odbiorcy (PLxxxxxxxxxxxxxxxxxxxxxxxxxx)" required>
            <label for="transfer_title">Tytuł przelewu:</label>
            <input type="text" name="transfer_title" placeholder="Tytuł przelewu" required>
            <label for="amount">Kwota:</label>
            <input type="number" step="0.01" name="amount" placeholder="Kwota przelewu" required>
            <label for="transfer_date">Data przelewu:</label>
            <input type="date" name="transfer_date" required>
            <button type="submit">Wyślij przelew</button>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php elseif (isset($success)): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
