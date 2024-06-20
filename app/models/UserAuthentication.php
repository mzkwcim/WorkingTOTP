<?php

class UserAuthentication {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
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

    public function verifyPassword($user_id, $password) {
        $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        return $user && password_verify($password, $user['password']);
    }

    public function setPasswordResetRequired($user_id) {
        $stmt = $this->pdo->prepare("UPDATE users SET password_reset_required = 1 WHERE id = ?");
        $stmt->execute([$user_id]);
    }

    public function isPasswordResetRequired($user_id) {
        $stmt = $this->pdo->prepare("SELECT password_reset_required FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }

    public function updatePassword($user_id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("UPDATE users SET password = ?, password_reset_required = 0 WHERE id = ?");
        $stmt->execute([$hashed_password, $user_id]);
    }
}
