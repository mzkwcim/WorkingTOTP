<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../db.php';
    $user_id = $_SESSION['user_id'];
    $password = $_POST['password'];

    // Pobierz hasło użytkownika z bazy danych
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Przekieruj do strony potwierdzenia szczegółów przelewu
        $_SESSION['recipient_name'] = $_POST['recipient_name'];
        $_SESSION['recipient_account'] = $_POST['recipient_account'];
        $_SESSION['transfer_title'] = $_POST['transfer_title'];
        $_SESSION['amount'] = $_POST['amount'];
        $_SESSION['transfer_date'] = $_POST['transfer_date'];
        header("Location: /2fatest/public/confirm_transfer");
        exit();
    } else {
        $error = "Nieprawidłowe hasło.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Potwierdzenie hasła</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Potwierdzenie hasła</h2>
        <form method="post">
            <input type="hidden" name="recipient_name" value="<?php echo htmlspecialchars($_SESSION['recipient_name']); ?>">
            <input type="hidden" name="recipient_account" value="<?php echo htmlspecialchars($_SESSION['recipient_account']); ?>">
            <input type="hidden" name="transfer_title" value="<?php echo htmlspecialchars($_SESSION['transfer_title']); ?>">
            <input type="hidden" name="amount" value="<?php echo htmlspecialchars($_SESSION['amount']); ?>">
            <input type="hidden" name="transfer_date" value="<?php echo htmlspecialchars($_SESSION['transfer_date']); ?>">
            <label for="password">Hasło:</label>
            <input type="password" name="password" required>
            <button type="submit">Potwierdź</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
