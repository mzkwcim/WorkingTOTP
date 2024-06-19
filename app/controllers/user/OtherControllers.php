<?php

class OtherControllers extends Controller {
    public function account() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        require '../db.php';

        $user_id = $_SESSION['user_id'];

        // Pobierz informacje o użytkowniku
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        $stmt = $pdo->prepare("SELECT * FROM userAccount WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user_account = $stmt->fetch();

        $full_name = $user['first_name'] . ' ' . $user['last_name'];
        $email = $user['email'];
        $account_number = $user_account['account_number'];
        $role = $user['role']; // Pobranie roli użytkownika

        $this->view('user/account', [
            'full_name' => $full_name,
            'email' => $email,
            'account_number' => $account_number,
            'role' => $role
        ]);
    }

    public function settings() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        require '../db.php';

        $user_id = $_SESSION['user_id'];

        // Pobierz aktualne dane użytkownika i konto
        $stmt = $pdo->prepare("
            SELECT u.username, ua.transaction_limit 
            FROM users u
            JOIN userAccount ua ON u.id = ua.user_id
            WHERE u.id = ?
        ");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        $this->view('user/settings', [
            'username' => $user['username'],
            'transaction_limit' => $user['transaction_limit']
        ]);
    }

    public function handleSettings() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        require '../db.php';

        $user_id = $_SESSION['user_id'];
        $new_username = $_POST['username'];
        $transaction_limit = $_POST['transaction_limit'];

        // Zaktualizuj dane użytkownika i limity
        $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->execute([$new_username, $user_id]);

        $stmt = $pdo->prepare("UPDATE userAccount SET transaction_limit = ? WHERE user_id = ?");
        $stmt->execute([$transaction_limit, $user_id]);

        // Pobierz zaktualizowane dane użytkownika
        $stmt = $pdo->prepare("
            SELECT u.username, ua.transaction_limit 
            FROM users u
            JOIN userAccount ua ON u.id = ua.user_id
            WHERE u.id = ?
        ");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        $success = "Dane zostały zaktualizowane.";

        $this->view('user/settings', [
            'username' => $user['username'],
            'transaction_limit' => $user['transaction_limit'],
            'success' => $success
        ]);
    }
}
