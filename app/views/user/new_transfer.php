<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /2fatest/public/login");
    exit();
}

require '../db.php';

$user_id = $_SESSION['user_id'];

// Pobierz informacje o nadawcy
$stmt = $pdo->prepare("SELECT u.first_name, u.last_name, ua.account_number, ua.transaction_limit FROM users u JOIN userAccount ua ON u.id = ua.user_id WHERE u.id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$sender_name = $user['first_name'] . ' ' . $user['last_name'];
$sender_account = $user['account_number'];
$transaction_limit = $user['transaction_limit'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_name = htmlspecialchars($_POST['recipient_name']);
    $recipient_account = htmlspecialchars($_POST['recipient_account']);
    $transfer_title = htmlspecialchars($_POST['transfer_title']);
    $amount = htmlspecialchars($_POST['amount']);
    $transfer_date = $_POST['transfer_date'];

    if ($amount <= 0) {
        $error = "Kwota musi być większa niż zero.";
    } elseif ($amount > $transaction_limit) {
        $error = "Kwota przekracza limit jednorazowej transakcji wynoszący " . number_format($transaction_limit, 2, ',', ' ') . " zł.";
    } elseif (!preg_match('/^PL\d{26}$/', $recipient_account)) {
        $error = "Numer konta musi zaczynać się od 'PL' i mieć 26 cyfr.";
    } else {
        // Przekieruj do strony potwierdzenia hasła
        $_SESSION['recipient_name'] = $recipient_name;
        $_SESSION['recipient_account'] = $recipient_account;
        $_SESSION['transfer_title'] = $transfer_title;
        $_SESSION['amount'] = $amount;
        $_SESSION['transfer_date'] = $transfer_date;
        header("Location: /2fatest/public/confirm_password");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Nowy przelew</title>
    <link rel="stylesheet" href="/2fatest/public/css/styles.css">
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var today = new Date().toISOString().split('T')[0];
            document.getElementsByName("transfer_date")[0].setAttribute('min', today);
        });
    </script>
</head>
<body>
    <div class="container">
        <a href="/2fatest/public/dashboard" class="back-button">&lt;</a>
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
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
