<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Papaj</title>
    <style>
        .container {
            text-align: center;
            margin-top: 50px;
        }
        .title {
            font-size: 48px;
            color: red;
            margin-bottom: 20px;
        }
        .image {
            width: 50%;
            height: auto;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-decoration: none;
            color: #000;
            font-weight: bold;
        }
        .back-button:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Kremówka</div>
        <img src="/2fatest/public/images/papaj.jfif" alt="Papaj" class="image">
        <form action="/2fatest/public/dashboard">
            <button type="submit" class="back-button">Zabierzcie mnie stąd</button>
        </form>
    </div>
</body>
</html>
