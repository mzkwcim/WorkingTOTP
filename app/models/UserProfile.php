<?php

class UserProfile {
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

    public function getFullUserDetails($user_id) {
        $user = $this->getUserById($user_id);
        $user_account = (new UserAccount($this->pdo))->getUserAccountByUserId($user_id);

        return [
            'full_name' => $user['first_name'] . ' ' . $user['last_name'],
            'email' => $user['email'],
            'account_number' => $user_account['account_number'],
            'role' => $user['role']
        ];
    }

    public function updateUser($user_id, $username) {
        $stmt = $this->pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->execute([$username, $user_id]);
    }

    public function setPasswordResetRequired($user_id) {
        $stmt = $this->pdo->prepare("UPDATE users SET password_reset_required = 1 WHERE id = ?");
        $stmt->execute([$user_id]);
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT id, username, email, first_name, last_name, role FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserWithAccountDetails($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT u.username, ua.transaction_limit 
            FROM users u
            JOIN userAccount ua ON u.id = ua.user_id
            WHERE u.id = ?
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }
}
