<?php

class TransfersController extends Controller {
    private $transferModel;
    private $userModel;

    public function __construct() {
        $pdo = require '../db.php';
        require_once '../models/Transfer.php';
        require_once '../models/User.php';
        $this->transferModel = new Transfer($pdo);
        $this->userModel = new User($pdo);
    }

    public function newTransfer() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /2fatest/login");
            exit();
        }

        $user_id = $_SESSION['user_id'];

        $user = $this->userModel->getUserById($user_id);
        $user_account = $this->userModel->getUserAccountByUserId($user_id);

        $sender_name = $user['first_name'] . ' ' . $user['last_name'];
        $sender_account = $user_account['account_number'];
        $transaction_limit = $user_account['transaction_limit'];

        $this->view('user/new_transfer', [
            'sender_name' => $sender_name,
            'sender_account' => $sender_account,
            'transaction_limit' => $transaction_limit
        ]);
    }

    public function handleNewTransfer() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /2fatest/login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $transaction_limit = $this->userModel->getTransactionLimit($user_id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recipient_name = htmlspecialchars($_POST['recipient_name']);
            $recipient_account = htmlspecialchars($_POST['recipient_account']);
            $transfer_title = htmlspecialchars($_POST['transfer_title']);
            $amount = htmlspecialchars($_POST['amount']);
            $transfer_date = $_POST['transfer_date'];

            if ($amount <= 0) {
                $error = "Kwota musi być większa niż zero.";
            } elseif ($amount > $transaction_limit) {
                $error = "Kwota przekracza limit jednorazowej transakcji wynoszący " . number_format($transaction_limit, 2, ',', ' ') . " zł.";
            } elseif (!preg_match('/^PL\d{26}$/', $recipient_account)) {
                $error = "Numer konta musi zaczynać się od 'PL' i mieć 26 cyfr.";
            } else {
                // Przekieruj do strony potwierdzenia hasła
                $_SESSION['recipient_name'] = $recipient_name;
                $_SESSION['recipient_account'] = $recipient_account;
                $_SESSION['transfer_title'] = $transfer_title;
                $_SESSION['amount'] = $amount;
                $_SESSION['transfer_date'] = $transfer_date;
                header("Location: /2fatest/confirm_password");
                exit();
            }
        }

        // Jeśli jest błąd, wróć do formularza z komunikatem o błędzie
        $this->view('user/new_transfer', [
            'error' => $error ?? null,
            'recipient_name' => $recipient_name ?? '',
            'recipient_account' => $recipient_account ?? '',
            'transfer_title' => $transfer_title ?? '',
            'amount' => $amount ?? '',
            'transfer_date' => $transfer_date ?? ''
        ]);
    }

    public function confirmPassword() {
        $this->view('user/confirm_password');
    }

    public function handleConfirmPassword() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /2fatest/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $password = $_POST['password'];

            if ($this->userModel->verifyPassword($user_id, $password)) {
                header("Location: /2fatest/confirm_transfer");
                exit();
            } else {
                $error = "Nieprawidłowe hasło.";
                $this->view('user/confirm_password', ['error' => $error]);
            }
        }
    }

    public function confirmTransfer() {
        session_start();
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['recipient_name'])) {
            header("Location: /2fatest/login");
            exit();
        }

        $this->view('user/confirm_transfer', [
            'recipient_name' => $_SESSION['recipient_name'],
            'recipient_account' => $_SESSION['recipient_account'],
            'transfer_title' => $_SESSION['transfer_title'],
            'amount' => $_SESSION['amount'],
            'transfer_date' => $_SESSION['transfer_date']
        ]);
    }

    public function handleConfirmTransfer() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /2fatest/login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $recipient_name = $_SESSION['recipient_name'];
        $recipient_account = $_SESSION['recipient_account'];
        $transfer_title = $_SESSION['transfer_title'];
        $amount = $_SESSION['amount'];
        $transfer_date = $_SESSION['transfer_date'];

        $user = $this->userModel->getUserById($user_id);
        $user_account = $this->userModel->getUserAccountByUserId($user_id);

        $sender_name = $user['first_name'] . ' ' . $user['last_name'];
        $sender_account = $user_account['account_number'];

        if (!$this->userModel->accountExists($recipient_account)) {
            $error = "Numer konta odbiorcy nie istnieje.";
            $this->view('user/confirm_transfer', ['error' => $error]);
        } else {
            try {
                $this->transferModel->createTransfer($user_id, $sender_name, $sender_account, $recipient_name, $recipient_account, $transfer_title, $amount, $transfer_date);
                $this->transferModel->updateBalances($sender_account, $recipient_account, $amount);

                unset($_SESSION['recipient_name']);
                unset($_SESSION['recipient_account']);
                unset($_SESSION['transfer_title']);
                unset($_SESSION['amount']);
                unset($_SESSION['transfer_date']);

                if ($amount == 69) {
                    header("Location: /2fatest/hidden/zboczuch");
                } elseif ($amount == 420) {
                    header("Location: /2fatest/hidden/parararara");
                } elseif ($amount == 2137) {
                    header("Location: /2fatest/hidden/papaj");
                } else {
                    header("Location: /2fatest/dashboard");
                }
                exit();
            } catch (Exception $e) {
                $error = "Wystąpił błąd podczas wysyłania przelewu.";
                $this->view('user/confirm_transfer', ['error' => $error]);
            }
        }
    }
}
