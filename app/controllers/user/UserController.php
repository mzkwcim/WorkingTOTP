<?php

class UserController extends Controller {
    public function dashboard() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /2fatest/login");
            exit();
        }

        require '../db.php';
        $user_id = $_SESSION['user_id'];

        // Pobierz dane użytkownika
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if (!$user) {
            header("Location: /2fatest/login");
            exit();
        }

        $role = $user['role'];

        // Pobierz saldo konta użytkownika
        $stmt = $pdo->prepare("SELECT balance, account_number FROM userAccount WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user_account = $stmt->fetch();
        $balance = $user_account['balance'];
        $account_number = $user_account['account_number'];

        // Pobierz historię transakcji użytkownika (zarówno wysłane, jak i otrzymane)
        $stmt = $pdo->prepare("SELECT t.*, 
            (CASE WHEN t.sender_account = :account_number THEN 'outgoing' ELSE 'incoming' END) AS transfer_type
            FROM transfers t 
            WHERE t.sender_account = :account_number OR t.recipient_account = :account_number
            ORDER BY t.transfer_date DESC");
        $stmt->execute(['account_number' => $account_number]);
        $transactions = $stmt->fetchAll();

        $this->view('user/dashboard', [
            'balance' => $balance,
            'transactions' => $transactions,
            'role' => $role
        ]);
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /2fatest/login");
        exit();
    }
}
