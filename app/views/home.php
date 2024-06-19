<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Witaj w banku pod pomarańczowym lwem</title>
    <link rel="stylesheet" href="/2fatest/public/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Witaj w banku pod pomarańczowym lwem</h1>
        <img src="/2fatest/public/images/Pomarańczowy_Lew.jpg" alt="Pomarańczowy lew" class="welcome-image">
        <div class="button-container">
            <form action="/2fatest/login" method="get">
                <button type="submit" class="button">Logowanie</button>
            </form>
            <form action="/2fatest/register" method="get">
                <button type="submit" class="button">Rejestracja</button>
            </form>
        </div>
    </div>
</body>
</html>
