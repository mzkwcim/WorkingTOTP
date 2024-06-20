<?php

class Transfer {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createTransfer($user_id, $sender_name, $sender_account, $recipient_name, $recipient_account, $transfer_title, $amount, $transfer_date) {
        $stmt = $this->pdo->prepare("INSERT INTO transfers (user_id, sender_name, sender_account, recipient_name, recipient_account, transfer_title, amount, transfer_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $sender_name, $sender_account, $recipient_name, $recipient_account, $transfer_title, $amount, $transfer_date]);
    }

    public function updateBalances($sender_account, $recipient_account, $amount) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("UPDATE userAccount SET balance = balance - ? WHERE account_number = ?");
            $stmt->execute([$amount, $sender_account]);

            $stmt = $this->pdo->prepare("UPDATE userAccount SET balance = balance + ? WHERE account_number = ?");
            $stmt->execute([$amount, $recipient_account]);

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
