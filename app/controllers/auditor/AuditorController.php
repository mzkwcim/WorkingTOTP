<?php

class AuditorController extends Controller {
    public function allOperations() {


        require '../db.php';

        // Pobierz wszystkie transakcje
        $stmt = $pdo->query("SELECT * FROM transfers ORDER BY transfer_date DESC");
        $transfers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('auditor/all_operations', ['transfers' => $transfers]);
    }
}
