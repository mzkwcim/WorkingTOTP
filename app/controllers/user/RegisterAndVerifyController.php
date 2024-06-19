<?php
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

class RegisterAndVerifyController extends Controller {
    private $userModel;

    public function __construct() {
        require_once '../app/models/User.php';
        $this->userModel = new User(require '../db.php');
    }

    public function register() {
        $this->view('register');
    }

    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            $password_confirm = htmlspecialchars($_POST['password_confirm']);
            $first_name = htmlspecialchars($_POST['first_name']);
            $last_name = htmlspecialchars($_POST['last_name']);
            $birth_date = $_POST['birth_date'];

            if ($password !== $password_confirm) {
                $error = "Hasła muszą się zgadzać.";
                $this->view('register', ['error' => $error]);
                return;
            }

            if (!$this->userModel->validateBirthDate($birth_date)) {
                $error = "Musisz mieć co najmniej 18 lat.";
                $this->view('register', ['error' => $error]);
                return;
            }

            $password_validation = $this->userModel->validatePassword($password);
            if ($password_validation !== true) {
                $this->view('register', ['error' => $password_validation]);
                return;
            }

            $g = new GoogleAuthenticator();
            $secret = $g->generateSecret();
            $qrCodeUrl = GoogleQrUrl::generate($username, $secret, 'TwojaFirma');

            $_SESSION['registration_data'] = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'birth_date' => $birth_date,
                'secret' => $secret,
                'qrCodeUrl' => $qrCodeUrl
            ];

            header("Location: /2fatest/verify");
            exit();
        }

        $this->view('register');
    }

    public function verify() {
        session_start();
        if (!isset($_SESSION['registration_data'])) {
            header("Location: /2fatest/register");
            exit();
        }

        $this->view('verify', [
            'qrCodeUrl' => $_SESSION['registration_data']['qrCodeUrl']
        ]);
    }

    public function handleVerify() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['totp_code'])) {
            $totp_code = $_POST['totp_code'];
            $registration_data = $_SESSION['registration_data'];

            $g = new GoogleAuthenticator();
            if ($g->checkCode($registration_data['secret'], $totp_code)) {
                $this->userModel->createUser(
                    $registration_data['username'],
                    $registration_data['email'],
                    $registration_data['password'],
                    $registration_data['first_name'],
                    $registration_data['last_name'],
                    $registration_data['birth_date'],
                    $registration_data['secret']
                );

                unset($_SESSION['registration_data']);
                header("Location: /2fatest/login");
                exit();
            } else {
                $error = "Nieprawidłowy kod TOTP.";
                $this->view('verify', ['error' => $error, 'qrCodeUrl' => $registration_data['qrCodeUrl']]);
            }
        }
    }
}

