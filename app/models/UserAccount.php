<?php

class UserAccount {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserAccountByUserId($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM userAccount WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }

    public function accountExists($account_number) {
        $stmt = $this->pdo->prepare("SELECT * FROM userAccount WHERE account_number = ?");
        $stmt->execute([$account_number]);
        return $stmt->fetch() !== false;
    }

    public function getTransactionLimit($user_id) {
        $stmt = $this->pdo->prepare("SELECT ua.transaction_limit FROM users u JOIN userAccount ua ON u.id = ua.user_id WHERE u.id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch()['transaction_limit'];
    }

    public function updateUserAccount($user_id, $transaction_limit) {
        $stmt = $this->pdo->prepare("UPDATE userAccount SET transaction_limit = ? WHERE user_id = ?");
        $stmt->execute([$transaction_limit, $user_id]);
    }

    public function getUserBalance($user_id) {
        $stmt = $this->pdo->prepare("SELECT balance, account_number FROM userAccount WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }

    public function createUserAccount($user_id) {
        $account_number = $this->generateAccountNumber();
        $stmt = $this->pdo->prepare("INSERT INTO userAccount (user_id, account_number, balance, transaction_limit) VALUES (?, ?, 0, 1000)");
        $stmt->execute([$user_id, $account_number]);
    }

    private function generateAccountNumber() {
        $prefix = 'PL';
        $number = '';

        for ($i = 0; $i < 26; $i++) {
            $number .= mt_rand(0, 9);
        }

        return $prefix . $number;
    }

    public function getUserTransactions($account_number) {
        $stmt = $this->pdo->prepare("SELECT t.*, 
            (CASE WHEN t.sender_account = :account_number THEN 'outgoing' ELSE 'incoming' END) AS transfer_type
            FROM transfers t 
            WHERE t.sender_account = :account_number OR t.recipient_account = :account_number
            ORDER BY t.transfer_date DESC");
        $stmt->execute(['account_number' => $account_number]);
        return $stmt->fetchAll();
    }
}
