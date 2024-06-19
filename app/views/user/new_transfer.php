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
        <a href="/2fatest/dashboard" class="back-button">&lt;</a>
        <h2>Nowy przelew</h2>
        <form method="post" action="/new_transfer">
            <label for="recipient_name">Odbiorca:</label>
            <input type="text" name="recipient_name" placeholder="Imię i nazwisko odbiorcy" value="<?php echo $recipient_name ?? ''; ?>" required>
            <label for="recipient_account">Numer konta:</label>
            <input type="text" name="recipient_account" placeholder="Numer konta odbiorcy (PLxxxxxxxxxxxxxxxxxxxxxxxxxx)" value="<?php echo $recipient_account ?? ''; ?>" required>
            <label for="transfer_title">Tytuł przelewu:</label>
            <input type="text" name="transfer_title" placeholder="Tytuł przelewu" value="<?php echo $transfer_title ?? ''; ?>" required>
            <label for="amount">Kwota:</label>
            <input type="number" step="0.01" name="amount" placeholder="Kwota przelewu" value="<?php echo $amount ?? ''; ?>" required>
            <label for="transfer_date">Data przelewu:</label>
            <input type="date" name="transfer_date" value="<?php echo $transfer_date ?? ''; ?>" required>
            <button type="submit">Wyślij przelew</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
