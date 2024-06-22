<?php

class UserController extends Controller {
    private $userModel;
    private $accountModel;
    private $authModel;

    public function __construct() {
        $pdo = require __DIR__ . '/../../../db.php';
        require_once __DIR__ . '/../../../app/models/UserProfile.php';
        require_once __DIR__ . '/../../../app/models/UserAccount.php';
        require_once __DIR__ . '/../../../app/models/UserAuthentication.php';
        $this->userModel = new UserProfile($pdo);
        $this->accountModel = new UserAccount($pdo);
        $this->authModel = new UserAuthentication($pdo);
    }

    public function dashboard() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /2fatest/login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($user_id);

        if ($user['password_reset_required']) {
            header("Location: /2fatest/reset_password");
            exit();
        }

        $user_details = $this->userModel->getFullUserDetails($user_id);
        $balance_details = $this->accountModel->getUserBalance($user_id);
        $transactions = $this->accountModel->getUserTransactions($balance_details['account_number']);

        $this->view('user/dashboard', [
            'balance' => $balance_details['balance'],
            'transactions' => $transactions,
            'role' => $user_details['role']
        ]);
    }

    public function resetPasswordForm() {
        $this->view('user/reset_password');
    }

    public function handleResetPassword() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /2fatest/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password !== $confirm_password) {
                $error = "Hasła muszą się zgadzać.";
                $this->view('user/reset_password', ['error' => $error]);
                return;
            }

            $this->authModel->updatePassword($user_id, $new_password);
            session_destroy();
            session_start();
            $_SESSION['message'] = "Twoje hasło zostało zresetowane poprawnie.";
            header("Location: /2fatest/login");
            exit();
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /2fatest");
        exit();
    }
}
