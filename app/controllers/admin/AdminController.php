<?php

class AdminController extends Controller {
    private $userProfileModel;
    private $userAccountModel;

    public function __construct() {
        require_once '../app/models/UserProfile.php';
        require_once '../app/models/UserAccount.php';
        $this->userProfileModel = new UserProfile(require '../db.php');
        $this->userAccountModel = new UserAccount(require '../db.php');
    }

    public function manageUsers() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /2fatest/login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $user = $this->userProfileModel->getUserById($user_id);

        if ($user['role'] !== 'admin') {
            header("Location: /2fatest/dashboard");
            exit();
        }

        $users = $this->userProfileModel->getAllUsers();

        $this->view('admin/manage_users', ['users' => $users]);
    }

    public function changeRole() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /2fatest/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $new_role = $_POST['role'];
            $this->userProfileModel->updateUserRole($user_id, $new_role);

            $users = $this->userProfileModel->getAllUsers();
            $success = "Rola użytkownika została zmieniona na: " . htmlspecialchars($new_role);

            error_log('Success message set: ' . $success);  

            $this->view('admin/manage_users', [
                'users' => $users,
                'success' => $success
            ]);
        }
    }

    public function resetPassword() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /2fatest/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['reset_password'];
            $this->userProfileModel->setPasswordResetRequired($user_id);

            $users = $this->userProfileModel->getAllUsers();
            $success = "Hasło użytkownika zostało zresetowane. Użytkownik musi zresetować hasło przy następnym logowaniu.";

            $this->view('admin/manage_users', [
                'users' => $users,
                'success' => $success
            ]);
        }
    }
}
