<?php
// начало PHP-программы

// Инициализация хранилища (store) и счётчика нажатий (count)
if (!isset($_GET['store'])) {
    $_GET['store'] = '';
} else {
    if (isset($_GET['key'])) {
        $_GET['store'] .= $_GET['key'];
    }
}

if (!isset($_GET['count'])) {
    $_GET['count'] = 0;
} else {
    $_GET['count']++;
}

// Выводим содержимое хранилища
echo '<div class="result">' . htmlspecialchars($_GET['store']) . '</div>';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Виртуальная клавиатура</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .result {
            width: 280px;
            height: 60px;
            border: 2px solid #333;
            border-radius: 5px;
            font-size: 24px;
            text-align: center;
            line-height: 60px;
            margin-bottom: 20px;
            background-color: #fafafa;
            overflow: hidden;
            word-break: break-all;
        }

        .keyboard {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .row {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .key {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: linear-gradient(to bottom, #4a90d9, #357abd);
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 20px;
            font-weight: bold;
            box-shadow: 0 3px 0 #2a5f8f;
            transition: all 0.15s;
        }

        .key:hover {
            background: linear-gradient(to bottom, #5a9ee9, #458acd);
            transform: translateY(-2px);
        }

        .key:active {
            transform: translateY(2px);
            box-shadow: 0 1px 0 #2a5f8f;
        }

        .reset {
            width: 100%;
            background: linear-gradient(to bottom, #e74c3c, #c0392b);
            box-shadow: 0 3px 0 #962d22;
            font-size: 16px;
        }

        .reset:hover {
            background: linear-gradient(to bottom, #f75c4c, #d0493b);
        }

        .reset:active {
            box-shadow: 0 1px 0 #962d22;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #555;
            font-size: 14px;
        }

        .counter {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
<div class="container">

    <!-- Клавиатура -->
    <div class="keyboard">
        <div class="row">
            <a href="/?key=1&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">1</a>
            <a href="/?key=2&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">2</a>
            <a href="/?key=3&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">3</a>
            <a href="/?key=4&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">4</a>
            <a href="/?key=5&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">5</a>
        </div>
        <div class="row">
            <a href="/?key=6&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">6</a>
            <a href="/?key=7&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">7</a>
            <a href="/?key=8&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">8</a>
            <a href="/?key=9&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">9</a>
            <a href="/?key=0&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">0</a>
        </div>
        <div class="row">
            <a href="/" class="key reset">СБРОС</a>
        </div>
    </div>

    <!-- Подвал с счётчиком нажатий -->
    <div class="footer">
        Всего нажатий: <span class="counter"><?php echo $_GET['count']; ?></span>
    </div>

</div>
</body>
</html>
