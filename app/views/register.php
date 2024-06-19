<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="/2fatest/public/css/styles.css">
    <script>
        function validateForm() {
            var password = document.forms["registerForm"]["password"].value;
            var confirmPassword = document.forms["registerForm"]["password_confirm"].value;
            if (password !== confirmPassword) {
                alert("Hasła nie są zgodne.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <a href="/2fatest/" class="back-button">&lt;</a>
        <div class="form-container">
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
                <?php if (isset($error)): ?>
                    <p><?php echo $error; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
