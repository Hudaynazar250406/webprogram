<?php
/**
 * Лабораторная работа № А-6
 * Тест математических знаний
 * Использование форм для передачи данных в программу PHP
 */

// ==================== ОБРАБОТКА ДАННЫХ ФОРМЫ ====================
$result = null;
$out_text = '';
$email_sent = false;
$form_submitted = false;

// Функция для преобразования строки с числом в float (с учетом запятой и точки)
function parseNumber($value) {
    $value = str_replace(',', '.', $value);
    return floatval($value);
}

// Функция для получения случайного числа (целого или дробного)
function getRandomNumber() {
    return mt_rand(0, 10000) / 100; // Числа от 0 до 100 с двумя знаками после запятой
}

// Проверяем, была ли отправлена форма
if (isset($_POST['A'])) {
    $form_submitted = true;
    
    // Получаем значения из формы
    $A = parseNumber($_POST['A']);
    $B = parseNumber($_POST['B']);
    $C = parseNumber($_POST['C']);
    $user_result = trim($_POST['user_result']);
    $task = $_POST['task'];
    $fio = htmlspecialchars($_POST['FIO'] ?? '');
    $group = htmlspecialchars($_POST['GROUP'] ?? '');
    $about = htmlspecialchars($_POST['ABOUT'] ?? '');
    $email = htmlspecialchars($_POST['MAIL'] ?? '');
    $view_mode = $_POST['view_mode'] ?? 'browser';
    $send_mail = isset($_POST['send_mail']);
    
    // Выполняем вычисление в зависимости от выбранной задачи
    switch ($task) {
        case 'triangle_area':
            // Площадь треугольника по формуле Герона
            $p = ($A + $B + $C) / 2; // Полупериметр
            $area_squared = $p * ($p - $A) * ($p - $B) * ($p - $C);
            $result = $area_squared > 0 ? round(sqrt($area_squared), 2) : 0;
            $task_name = 'ПЛОЩАДЬ ТРЕУГОЛЬНИКА';
            break;
            
        case 'triangle_perimeter':
            // Периметр треугольника
            $result = round($A + $B + $C, 2);
            $task_name = 'ПЕРИМЕТР ТРЕУГОЛЬНИКА';
            break;
            
        case 'parallelepiped_volume':
            // Объем параллелепипеда
            $result = round($A * $B * $C, 2);
            $task_name = 'ОБЪЕМ ПАРАЛЛЕЛЕПИПЕДА';
            break;
            
        case 'mean':
            // Среднее арифметическое
            $result = round(($A + $B + $C) / 3, 2);
            $task_name = 'СРЕДНЕЕ АРИФМЕТИЧЕСКОЕ';
            break;
            
        case 'mean_geometric':
            // Среднее геометрическое
            $product = $A * $B * $C;
            $result = $product > 0 ? round(pow($product, 1/3), 2) : 0;
            $task_name = 'СРЕДНЕЕ ГЕОМЕТРИЧЕСКОЕ';
            break;
            
        case 'max_value':
            // Максимальное значение
            $result = max($A, $B, $C);
            $task_name = 'МАКСИМАЛЬНОЕ ЗНАЧЕНИЕ';
            break;
            
        case 'sum_squares':
            // Сумма квадратов
            $result = round(pow($A, 2) + pow($B, 2) + pow($C, 2), 2);
            $task_name = 'СУММА КВАДРАТОВ';
            break;
            
        case 'discriminant':
            // Дискриминант квадратного уравнения (A*x^2 + B*x + C = 0)
            $result = round(pow($B, 2) - 4 * $A * $C, 2);
            $task_name = 'ДИСКРИМИНАНТ КВАДРАТНОГО УРАВНЕНИЯ';
            break;
            
        default:
            $result = 0;
            $task_name = 'НЕИЗВЕСТНАЯ ЗАДАЧА';
    }
    
    // Проверяем ответ пользователя
    $user_result_num = parseNumber($user_result);
    $test_passed = (abs($result - $user_result_num) < 0.01); // Допуск на погрешность округления
    
    // Формируем отчет
    $out_text .= "ФИО: {$fio}<br>";
    $out_text .= "Группа: {$group}<br>";
    if (!empty($about)) {
        $out_text .= "<br>" . nl2br($about) . "<br>";
    }
    $out_text .= "<br>Решаемая задача: {$task_name}<br>";
    $out_text .= "Входные данные: A = {$A}, B = {$B}, C = {$C}<br>";
    
    if ($user_result === '') {
        $out_text .= "<br><em>Задача самостоятельно решена не была</em><br>";
    } else {
        $out_text .= "Предполагаемый результат: {$user_result}<br>";
    }
    
    $out_text .= "Вычисленный результат: {$result}<br>";
    
    if ($test_passed) {
        $out_text .= "<br><b style='color: #4CAF50;'>ТЕСТ ПРОЙДЕН</b><br>";
    } else {
        $out_text .= "<br><b style='color: #f44336;'>ОШИБКА: ТЕСТ НЕ ПРОЙДЕН!</b><br>";
    }
    
    // Отправка email если установлен флажок
    if ($send_mail && !empty($email)) {
        $email_text = str_replace('<br>', "\r\n", $out_text);
        $email_text = strip_tags($email_text);
        $headers = "From: auto@math-test.ru\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
        
        @mail($email, 'Результат тестирования', $email_text, $headers);
        $email_sent = true;
    }
}

// Генерируем случайные начальные значения
$default_A = getRandomNumber();
$default_B = getRandomNumber();
$default_C = getRandomNumber();

// Если есть GET-параметры (после нажатия "Повторить тест"), используем их
$prefill_fio = htmlspecialchars($_GET['F'] ?? '');
$prefill_group = htmlspecialchars($_GET['G'] ?? '');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тест математических знаний - Лабораторная работа А-6</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            padding: 40px 20px;
            color: #333;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .content {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .form-group label {
            width: 150px;
            font-weight: 500;
            color: #555;
            font-size: 14px;
        }
        
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group select,
        .form-group textarea {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #e94560;
        }
        
        .form-group textarea {
            min-height: 80px;
            resize: vertical;
            font-family: inherit;
        }
        
        .form-group.checkbox-group {
            align-items: flex-start;
        }
        
        .form-group.checkbox-group label {
            width: auto;
            margin-left: 10px;
            cursor: pointer;
        }
        
        .form-group.checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #e94560;
        }
        
        .hidden {
            display: none !important;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(233, 69, 96, 0.4);
        }
        
        /* Стили для результатов */
        .results {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .results h2 {
            color: #1a1a2e;
            margin-bottom: 20px;
            font-size: 20px;
            border-bottom: 2px solid #e94560;
            padding-bottom: 10px;
        }
        
        .results-content {
            line-height: 1.8;
            color: #444;
        }
        
        .email-notice {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border-left: 4px solid #28a745;
        }
        
        .back-link {
            display: inline-block;
            background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
            color: white;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            background: white;
            color: #e94560;
            border-color: #e94560;
        }
        
        /* Стили для печати */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .container {
                box-shadow: none;
                max-width: 100%;
            }
            
            .header {
                background: #333 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .back-link,
            .btn-submit,
            form {
                display: none !important;
            }
            
            .results {
                page-break-inside: avoid;
            }
        }
        
        /* Адаптивность */
        @media (max-width: 600px) {
            .form-group {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .form-group label {
                width: 100%;
                margin-bottom: 5px;
            }
            
            .form-group input[type="text"],
            .form-group input[type="email"],
            .form-group select,
            .form-group textarea {
                width: 100%;
            }
        }
        
        .section-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        
        .numbers-section {
            background: #f0f4f8;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .numbers-section .form-group label {
            color: #0f3460;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Тест математических знаний</h1>
            <p>Лабораторная работа № А-6</p>
        </div>
        
        <div class="content">
            <?php if ($form_submitted && isset($view_mode) && $view_mode === 'browser'): ?>
                <!-- РЕЗУЛЬТАТЫ (версия для браузера) -->
                <div class="results">
                    <h2>Результаты тестирования</h2>
                    <div class="results-content">
                        <?php echo $out_text; ?>
                    </div>
                    
                    <?php if ($email_sent): ?>
                        <div class="email-notice">
                            Результаты теста были автоматически отправлены на e-mail: <?php echo $email; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <a href="?F=<?php echo urlencode($fio); ?>&G=<?php echo urlencode($group); ?>" class="back-link">Повторить тест</a>
                
            <?php elseif ($form_submitted && isset($view_mode) && $view_mode === 'print'): ?>
                <!-- РЕЗУЛЬТАТЫ (версия для печати) -->
                <div class="results">
                    <h2>Результаты тестирования</h2>
                    <div class="results-content">
                        <?php echo $out_text; ?>
                    </div>
                    
                    <?php if ($email_sent): ?>
                        <div class="email-notice">
                            Результаты теста были автоматически отправлены на e-mail: <?php echo $email; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
            <?php else: ?>
                <!-- ФОРМА -->
                <form name="math_test" method="post" action="">
                    
                    <div class="section-title">Личная информация</div>
                    
                    <div class="form-group">
                        <label for="FIO">ФИО:</label>
                        <input type="text" id="FIO" name="FIO" value="<?php echo $prefill_fio; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="GROUP">Номер группы:</label>
                        <input type="text" id="GROUP" name="GROUP" value="<?php echo $prefill_group; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ABOUT">Немного о себе:</label>
                        <textarea id="ABOUT" name="ABOUT" placeholder="Расскажите немного о себе..."></textarea>
                    </div>
                    
                    <div class="section-title">Математическая задача</div>
                    
                    <div class="numbers-section">
                        <div class="form-group">
                            <label for="A">Значение A:</label>
                            <input type="text" id="A" name="A" value="<?php echo $default_A; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="B">Значение B:</label>
                            <input type="text" id="B" name="B" value="<?php echo $default_B; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="C">Значение C:</label>
                            <input type="text" id="C" name="C" value="<?php echo $default_C; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="task">Тип задачи:</label>
                        <select id="task" name="task" required>
                            <option value="triangle_area">Площадь треугольника</option>
                            <option value="triangle_perimeter">Периметр треугольника</option>
                            <option value="parallelepiped_volume">Объем параллелепипеда</option>
                            <option value="mean" selected>Среднее арифметическое</option>
                            <option value="mean_geometric">Среднее геометрическое</option>
                            <option value="max_value">Максимальное значение</option>
                            <option value="sum_squares">Сумма квадратов</option>
                            <option value="discriminant">Дискриминант квадратного уравнения</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="user_result">Ваш ответ:</label>
                        <input type="text" id="user_result" name="user_result" placeholder="Введите ваш ответ...">
                    </div>
                    
                    <div class="section-title">Настройки</div>
                    
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="send_mail" name="send_mail" onClick="toggleEmailField()">
                        <label for="send_mail">Отправить результат теста по e-mail</label>
                    </div>
                    
                    <div class="form-group hidden" id="email_field">
                        <label for="MAIL">Ваш e-mail:</label>
                        <input type="email" id="MAIL" name="MAIL" placeholder="example@mail.ru">
                    </div>
                    
                    <div class="form-group">
                        <label for="view_mode">Режим отображения:</label>
                        <select id="view_mode" name="view_mode">
                            <option value="browser" selected>Версия для просмотра в браузере</option>
                            <option value="print">Версия для печати</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="justify-content: center; margin-top: 30px;">
                        <button type="submit" class="btn-submit">Проверить</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Функция для показа/скрытия поля email
        function toggleEmailField() {
            var checkbox = document.getElementById('send_mail');
            var emailField = document.getElementById('email_field');
            
            if (checkbox.checked) {
                emailField.classList.remove('hidden');
            } else {
                emailField.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
