<?php

class AdminController extends Controller {
    public function manageUsers() {

        require '../db.php';

        // Pobierz wszystkich użytkowników
        $stmt = $pdo->query("SELECT id, username, email, first_name, last_name, role FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('admin/manage_users', ['users' => $users]);
    }

    public function resetPassword() {
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /2fatest/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
            require '../db.php';
            $user_id = $_POST['reset_password'];
            $stmt = $pdo->prepare("UPDATE users SET password_reset_required = 1 WHERE id = ?");
            $stmt->execute([$user_id]);
            header("Location: /2fatest/manage_users");
            exit();
        }
    }
}
