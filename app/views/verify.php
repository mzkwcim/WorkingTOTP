<?php
session_start();
require '../../db.php';
require '../../vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleQrUrl;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['totp_code'])) {
        $totp_code = $_POST['totp_code'];
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $password = $_SESSION['password'];
        $first_name = $_SESSION['first_name'];
        $last_name = $_SESSION['last_name'];
        $birth_date = $_SESSION['birth_date'];
        $secret = $_SESSION['secret'];

        $g = new GoogleAuthenticator();
        if ($g->checkCode($secret, $totp_code)) {
            $pdo->beginTransaction();
            try {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Generowanie numeru konta
                function generateAccountNumber($username, $email) {
                    $hash = hash('sha256', $username . $email);
                    $accountNumber = '';
                    
                    // Weź pierwsze 26 cyfr z hasha
                    for ($i = 0; $i < 26; $i++) {
                        $accountNumber .= hexdec($hash[$i]) % 10;
                    }

                    // Dodaj prefiks IBAN
                    $accountNumber = 'PL' . $accountNumber;
                    return $accountNumber;
                }
                
                $accountNumber = generateAccountNumber($username, $email);

                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, secret, totp_verified, role, first_name, last_name, birth_date) VALUES (?, ?, ?, ?, 0, 'user', ?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password, $secret, $first_name, $last_name, $birth_date]);

                $user_id = $pdo->lastInsertId();

                $stmt = $pdo->prepare("INSERT INTO userAccount (user_id, balance, account_number) VALUES (?, 0.00, ?)");
                $stmt->execute([$user_id, $accountNumber]);

                $pdo->commit();

                header("Location: dashboard.php");
                exit();
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Wystąpił błąd podczas rejestracji.";
            }
        } else {
            $error = "Nieprawidłowy kod TOTP";
        }
    } elseif (isset($_POST['secret'])) {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['first_name'] = $_POST['first_name'];
        $_SESSION['last_name'] = $_POST['last_name'];
        $_SESSION['birth_date'] = $_POST['birth_date'];
        $_SESSION['secret'] = $_POST['secret'];
        $_SESSION['qrCodeUrl'] = $_POST['qrCodeUrl'];

        $qrCodeUrl = $_POST['qrCodeUrl'];
    }
} else {
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Weryfikacja</title>
    <link rel="stylesheet" href="/2fatest/public/css/styles.css">
    <style>
        .back-button {
            display: inline-block;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-decoration: none;
            color: #000;
            font-weight: bold;
            margin-bottom: 10px;
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10; /* Ensure button is above other elements */
        }
        .back-button:hover {
            background-color: #e0e0e0;
        }
        .form-container {
            text-align: center;
            position: relative;
        }
        .form-container h2 {
            text-align: center;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            position: relative;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/2fatest/public/" class="back-button">&lt;</a>
        <div class="form-container">
            <h2>Zeskanuj ten kod QR za pomocą Google Authenticator</h2>
            <div class="qr-code">
                <img src="<?php echo htmlspecialchars($qrCodeUrl); ?>" alt="Kod QR">
            </div>
            <form method="post">
                <label for="totp_code">Kod TOTP</label>
                <input type="text" name="totp_code" placeholder="Wprowadź kod TOTP" required>
                <button type="submit">Zweryfikuj</button>
                <?php if (isset($error)): ?>
                    <p><?php echo $error; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
