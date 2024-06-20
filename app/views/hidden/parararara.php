<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Weeeeeeed</title>
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
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-decoration: none;
            color: #000;
            font-weight: bold;
            cursor: pointer;
        }
        .back-button:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Weeeeeeed</div>
        <img src="/2fatest/images/snoopdogg.gif" alt="SnoopDogg" class="image">
        <form action="/2fatest/dashboard" method="get">
            <button type="submit" class="back-button">Zabierzcie mnie stąd</button>
        </form>
    </div>
</body>
</html>
