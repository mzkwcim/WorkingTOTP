<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Witaj w banku pod pomarańczowym lwem</title>
    <link rel="stylesheet" href="/2fatest/css/styles.css">
</head>
<body>
    <div class="blur-overlay"></div>
    <div class="container">
        <h1>Witaj w banku pod pomarańczowym lwem</h1>
        <img src="/2fatest/images/Pomaranczowy_Lew.jpg" alt="Pomaranczowy_Lew">
        <br>
        <button onclick="window.location.href='/2fatest/login'">Logowanie</button>
        <button onclick="window.location.href='/2fatest/register'">Rejestracja</button><br>
        <button id="hiddenButton" class="hidden-button" onclick="showModal()">Niewidoczny przycisk</button>
    </div>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img src="/2fatest/images/lech.jpg" alt="lech" class="modal-image">
            <p>No tak...</p>
        </div>
    </div>

    <script src="/2fatest/js/modal.js" defer></script>
</body>
</html>
