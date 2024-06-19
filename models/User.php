<?php

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createUser($username, $email, $password, $first_name, $last_name, $birth_date, $secret) {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, birth_date, secret, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'user')");
        $stmt->execute([$username, $email, password_hash($password, PASSWORD_BCRYPT), $first_name, $last_name, $birth_date, $secret]);
        return $this->pdo->lastInsertId();
    }

    public function getUserByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function getUserAccountByUserId($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM userAccount WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }

    public function accountExists($account_number) {
        $stmt = $this->pdo->prepare("SELECT * FROM userAccount WHERE account_number = ?");
        $stmt->execute([$account_number]);
        return $stmt->fetch() !== false;
    }

    public function validatePassword($password) {
        if (strlen($password) < 12) {
            return "Hasło musi mieć co najmniej 12 znaków.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return "Hasło musi zawierać co najmniej jedną wielką literę.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            return "Hasło musi zawierać co najmniej jedną małą literę.";
        }
        if (!preg_match('/\d/', $password)) {
            return "Hasło musi zawierać co najmniej jedną cyfrę.";
        }
        if (!preg_match('/[\W]/', $password)) {
            return "Hasło musi zawierać co najmniej jeden znak specjalny.";
        }
        return true;
    }

    public function validateBirthDate($birth_date) {
        $birth_date = new DateTime($birth_date);
        $today = new DateTime();
        $age = $today->diff($birth_date)->y;
        return $age >= 18;
    }
    public function getTransactionLimit($user_id) {
        $stmt = $this->pdo->prepare("SELECT ua.transaction_limit FROM users u JOIN userAccount ua ON u.id = ua.user_id WHERE u.id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch()['transaction_limit'];
    }
    public function verifyPassword($user_id, $password) {
        $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        return $user && password_verify($password, $user['password']);
    }
}
