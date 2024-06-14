<?php
require 'db.php';

// Pobierz dzisiejszą datę
$today = date('Y-m-d');

// Pobierz wszystkie zaplanowane przelewy na dzisiejszą datę
$stmt = $pdo->prepare("SELECT * FROM pending_transfers WHERE transfer_date = ?");
$stmt->execute([$today]);
$transfers = $stmt->fetchAll();

foreach ($transfers as $transfer) {
    $user_id = $transfer['user_id'];
    $sender_name = $transfer['sender_name'];
    $sender_account = $transfer['sender_account'];
    $recipient_name = $transfer['recipient_name'];
    $recipient_account = $transfer['recipient_account'];
    $transfer_title = $transfer['transfer_title'];
    $amount = $transfer['amount'];
    $transfer_date = $transfer['transfer_date'];

    // Rozpocznij transakcję
    $pdo->beginTransaction();
    try {
        // Dodaj przelew do tabeli transfers
        $stmt = $pdo->prepare("INSERT INTO transfers (user_id, sender_name, sender_account, recipient_name, recipient_account, transfer_title, amount, transfer_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $sender_name, $sender_account, $recipient_name, $recipient_account, $transfer_title, $amount, $transfer_date]);

        // Zaktualizuj saldo odbiorcy
        $stmt = $pdo->prepare("UPDATE userAccount SET balance = balance + ? WHERE account_number = ?");
        $stmt->execute([$amount, $recipient_account]);

        // Usuń przelew z tabeli pending_transfers
        $stmt = $pdo->prepare("DELETE FROM pending_transfers WHERE id = ?");
        $stmt->execute([$transfer['id']]);

        // Zatwierdź transakcję
        $pdo->commit();
    } catch (Exception $e) {
        // Wycofaj transakcję w razie błędu
        $pdo->rollBack();
        error_log("Błąd podczas przetwarzania przelewu: " . $e->getMessage());
    }
}
?>
