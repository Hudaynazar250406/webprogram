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
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .result {
            width: 300px;
            height: 60px;
            border: 2px solid #333;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
            background-color: #fafafa;
            text-align: center;
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
            display: inline-block;
            width: 50px;
            height: 50px;
            background: linear-gradient(to bottom, #4a90d9, #357abd);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            transition: all 0.2s;
            box-shadow: 0 3px 0 #2a5f8f;
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
            color: #666;
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
        <?php
        // Инициализация хранилища
        if (!isset($_GET['store'])) {
            $_GET['store'] = '';
        }
        
        // Инициализация счетчика нажатий
        if (!isset($_GET['count'])) {
            $_GET['count'] = 0;
        }
        
        // Обработка нажатия кнопки
        if (isset($_GET['key'])) {
            if ($_GET['key'] == 'reset') {
                $_GET['store'] = ''; // Очистить хранилище
            } else {
                $_GET['store'] .= $_GET['key']; // Добавить цифру в хранилище
            }
            // Увеличить счетчик нажатий
            $_GET['count']++;
        }
        ?>
        
        <!-- Окно просмотра результата -->
        <div class="result"><?php echo htmlspecialchars($_GET['store']); ?></div>
        
        <!-- Клавиатура -->
        <div class="keyboard">
            <div class="row">
                <a href="?key=1&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">1</a>
                <a href="?key=2&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">2</a>
                <a href="?key=3&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">3</a>
                <a href="?key=4&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">4</a>
                <a href="?key=5&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">5</a>
            </div>
            <div class="row">
                <a href="?key=6&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">6</a>
                <a href="?key=7&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">7</a>
                <a href="?key=8&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">8</a>
                <a href="?key=9&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">9</a>
                <a href="?key=0&store=<?php echo urlencode($_GET['store']); ?>&count=<?php echo $_GET['count']; ?>" class="key">0</a>
            </div>
            <div class="row">
                <a href="?key=reset&count=<?php echo $_GET['count']; ?>" class="key reset">СБРОС</a>
            </div>
        </div>
        
        <!-- Подвал со счетчиком нажатий -->
        <div class="footer">
            Общее число нажатий кнопок: <span class="counter"><?php echo $_GET['count']; ?></span>
        </div>
    </div>
</body>
</html>
