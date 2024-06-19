<?php
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
class LoginAndVerifyController extends Controller {
    public function login() {
        $this->view('login'); // Zaktualizowano ścieżkę do widoku login
    }

    public function handleLogin() {
        session_start();
        require '../db.php';

        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: /verify_login_totp");
        } else {
            $error = "Invalid username or password.";
            $this->view('login', ['error' => $error]); // Zaktualizowano ścieżkę do widoku login
        }
    }

    public function verifyLoginTotp() {
        $this->view('verify_login_totp');
    }

    public function handleVerifyLoginTotp() {
        session_start();
        require '../db.php';
        require '../vendor/autoload.php';


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $totp_code = $_POST['totp_code'];
            $user_id = $_SESSION['user_id'];

            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();

            if ($user) {
                $g = new GoogleAuthenticator();
                if ($g->checkCode($user['secret'], $totp_code)) {
                    header("Location: /dashboard");
                } else {
                    $error = "Invalid TOTP code.";
                    $this->view('verify_login_totp', ['error' => $error]);
                }
            } else {
                header("Location: /login");
            }
        }
    }
}
