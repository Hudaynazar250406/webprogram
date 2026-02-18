<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мерданов Худайназар | Группа 241-352 | Лабораторная № А-2 | Вариант 16 (WHILE)</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="https://via.placeholder.com/150x80/ffffff/2c3e50?text=LOGO" alt="Логотип университета">
        </div>
        <h1>Лабораторная работа № А-2 (Цикл с предусловием WHILE)</h1>
        <div class="student-info">
            <p><strong>Выполнил:</strong> Мерданов Худайназар</p>
            <p><strong>Группа:</strong> 241-352</p>
            <p><strong>Вариант:</strong> 16</p>
        </div>
    </header>

    <main>
        <h2>Результаты табулирования функции (цикл WHILE)</h2>
        
        <?php
        // Инициализация переменных
        $start_value = -10;
        $encounting = 30;
        $step = 1;
        $min_value = -50;
        $max_value = 100;
        $type = 'D';
        
        $x = $start_value;
        $values = array();
        $actual_count = 0;
        $i = 0;
        $f = 0;
        
        // Функция для вычисления
        function calculateFunction($x) {
            if ($x <= 0) {
                $f = ($x * $x) / 10;
            } elseif ($x < 15) {
                if ($x == 10) {
                    return 'error';
                }
                $f = ($x + 5) / ($x - 10);
            } else {
                if ($x - 14 < 0) {
                    return 'error';
                }
                $f = sqrt($x - 14) + 2;
            }
            return round($f, 3);
        }
        
        // Открытие контейнеров
        if ($type == 'B') echo '<ul class="output-list">';
        elseif ($type == 'C') echo '<ol class="output-list">';
        elseif ($type == 'E') echo '<div class="output-blocks">';
        elseif ($type == 'D') {
            echo '<table class="output-table">';
            echo '<thead><tr><th>№</th><th>Аргумент (x)</th><th>Значение f(x)</th></tr></thead><tbody>';
        }
        
        // ============================================
        // Цикл с предусловием WHILE
        // Условие: выполнять пока $i < $encounting И ($f в пределах диапазона ИЛИ первая итерация)
        // ============================================
        
        while ($i < $encounting && ($f >= $min_value && $f < $max_value || $i == 0)) {
            $f = calculateFunction($x);
            $is_error = ($f === 'error');
            
            if (!$is_error) {
                if ($f >= $max_value || $f < $min_value) {
                    break;
                }
                $values[] = $f;
            }
            
            $actual_count++;
            
            // Вывод в зависимости от типа
            switch ($type) {
                case 'A':
                    echo '<div class="output-text">f(' . $x . ')=' . $f . '</div><br>';
                    break;
                case 'B':
                    echo '<li>f(' . $x . ')=' . $f . '</li>';
                    break;
                case 'C':
                    echo '<li>f(' . $x . ')=' . $f . '</li>';
                    break;
                case 'D':
                    echo '<tr><td>' . ($i + 1) . '</td><td>' . $x . '</td><td>' . $f . '</td></tr>';
                    break;
                case 'E':
                    echo '<div class="block-item">f(' . $x . ')=' . $f . '</div>';
                    break;
            }
            
            // Увеличение счетчиков
            $i++;
            $x += $step;
        }
        
        // Закрытие контейнеров
        if ($type == 'B') echo '</ul>';
        elseif ($type == 'C') echo '</ol>';
        elseif ($type == 'E') {
            echo '</div><div style="clear: both;"></div>';
        }
        elseif ($type == 'D') echo '</tbody></table>';
        
        // Статистика
        echo '<div class="statistics">';
        echo '<h3>Статистика (WHILE)</h3>';
        if (count($values) > 0) {
            echo '<p>Максимум: ' . max($values) . '</p>';
            echo '<p>Минимум: ' . min($values) . '</p>';
            echo '<p>Среднее: ' . round(array_sum($values) / count($values), 3) . '</p>';
            echo '<p>Сумма: ' . round(array_sum($values), 3) . '</p>';
        }
        echo '</div>';
        ?>
    </main>

    <footer>
        <p><strong>Тип верстки:</strong> <?php echo $type; ?> (Цикл WHILE)</p>
        <p>&copy; 2025 Мерданов Худайназар, Группа 241-352</p>
    </footer>
</body>
</html>
