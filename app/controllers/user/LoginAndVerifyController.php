<?php
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

class LoginAndVerifyController extends Controller {
    private $userModel;

    public function __construct() {
        $pdo = require __DIR__ . '/../../../db.php';
        require_once __DIR__ . '/../../../app/models/UserProfile.php';
        $this->userModel = new UserProfile($pdo);
    }

    public function login() {
        $this->view('login');
    }

    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->userModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['secret'] = $user['secret'];

                if ($user['password_reset_required'] == 1) {
                    header("Location: /2fatest/reset_password");
                    exit();
                } else {
                    header("Location: /2fatest/verify_login_totp");
                    exit();
                }
            } else {
                $error = "Nieprawidłowe dane logowania";
                $this->view('login', ['error' => $error]);
            }
        }
    }

    public function verifyLoginTotp() {
        $this->view('verify_login_totp');
    }

    public function handleVerifyLoginTotp() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $totp_code = $_POST['totp_code'];
            $user_id = $_SESSION['user_id'];

            $user = $this->userModel->getUserById($user_id);
            $g = new GoogleAuthenticator();

            if ($user && $g->checkCode($user['secret'], $totp_code)) {
                header("Location: /2fatest/dashboard");
                exit();
            } else {
                $error = "Nieprawidłowy kod TOTP";
                $this->view('verify_login_totp', ['error' => $error]);
            }
        }
    }
}
