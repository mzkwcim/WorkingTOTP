<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="/2fatest/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="back-button-container">
                <a href="/2fatest/" class="back-button">&lt;</a>
            </div>
            <h2>Rejestracja</h2>
            <form name="registerForm" method="post" onsubmit="return validateForm()">
                <input type="text" name="username" placeholder="Nazwa użytkownika" required>
                <input type="password" name="password" placeholder="Hasło" required>
                <input type="password" name="password_confirm" placeholder="Potwierdź hasło" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="first_name" placeholder="Imię" required>
                <input type="text" name="last_name" placeholder="Nazwisko" required>
                <input type="date" name="birth_date" placeholder="Data urodzenia" required>
                <button type="submit">Zarejestruj się</button>
            </form>
        </div>
    </div>
    <script src="/2fatest/js/script.js"></script>
</body>
</html>
