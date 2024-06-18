<?php

class UserController {
    public function register() {
        require '../app/views/register.php';
    }

    public function store() {
        session_start();
        require '../db.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            $email = $_POST['email'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $birth_date = $_POST['birth_date'];

            if ($password !== $password_confirm) {
                $error = "Hasła nie są zgodne.";
                require '../app/views/register.php';
                return;
            }

            $password = password_hash($password, PASSWORD_DEFAULT);

            $secret = (new \Sonata\GoogleAuthenticator\GoogleAuthenticator())->generateSecret();

            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, first_name, last_name, birth_date, secret) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $password, $email, $first_name, $last_name, $birth_date, $secret]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['secret'] = $secret;

            // Przekierowanie na trasę verify
            header("Location: /2fatest/verify");
            exit();
        }
    }
}
