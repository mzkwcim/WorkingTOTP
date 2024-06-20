<?php

class OtherControllers extends Controller {
    private $userProfileModel;
    private $userAccountModel;

    public function __construct() {
        $pdo = require '../db.php';
        require_once '../app/models/UserProfile.php';
        require_once '../app/models/UserAccount.php';
        $this->userProfileModel = new UserProfile($pdo);
        $this->userAccountModel = new UserAccount($pdo);
    }

    public function account() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /2fatest/login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $userDetails = $this->userProfileModel->getFullUserDetails($user_id);

        $this->view('user/account', $userDetails);
    }

    public function settings() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /2fatest/login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $user = $this->userProfileModel->getUserWithAccountDetails($user_id);

        $this->view('user/settings', [
            'username' => $user['username'],
            'transaction_limit' => $user['transaction_limit']
        ]);
    }

    public function handleSettings() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /2fatest/login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $new_username = $_POST['username'];
        $transaction_limit = $_POST['transaction_limit'];

        $this->userProfileModel->updateUser($user_id, $new_username);
        $this->userAccountModel->updateUserAccount($user_id, $transaction_limit);

        $user = $this->userProfileModel->getUserWithAccountDetails($user_id);
        $success = "Dane zostały zaktualizowane.";

        $this->view('user/settings', [
            'username' => $user['username'],
            'transaction_limit' => $user['transaction_limit'],
            'success' => $success
        ]);
    }
}
