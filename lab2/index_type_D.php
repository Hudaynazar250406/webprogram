<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мерданов Худайназар | Группа 241-352 | Лабораторная № А-2 | Тип D</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="https://via.placeholder.com/150x80/ffffff/2c3e50?text=LOGO" alt="Логотип университета">
        </div>
        <h1>Лабораторная работа № А-2 (Тип верстки D)</h1>
        <div class="student-info">
            <p><strong>Выполнил:</strong> Мерданов Худайназар</p>
            <p><strong>Группа:</strong> 241-352</p>
            <p><strong>Вариант:</strong> 16</p>
        </div>
    </header>

    <main>
        <h2>Результаты табулирования функции (Табличная верстка)</h2>
        
        <?php
        $start_value = -5;
        $encounting = 15;
        $step = 1;
        $min_value = -100;
        $max_value = 100;
        $type = 'D';
        $x = $start_value;
        $values = array();
        
        function calculateFunction($x) {
            if ($x <= 0) {
                $f = ($x * $x) / 10;
            } elseif ($x < 15) {
                if ($x == 10) return 'error';
                $f = ($x + 5) / ($x - 10);
            } else {
                $f = sqrt($x - 14) + 2;
            }
            return round($f, 3);
        }
        
        // Тип D: табличная верстка
        echo '<table class="output-table">';
        echo '<thead><tr><th>№</th><th>Аргумент (x)</th><th>Значение f(x)</th></tr></thead>';
        echo '<tbody>';
        
        for ($i = 0; $i < $encounting; $i++, $x += $step) {
            $f = calculateFunction($x);
            $is_error = ($f === 'error');
            
            if (!$is_error) {
                if ($f >= $max_value || $f < $min_value) break;
                $values[] = $f;
            }
            
            echo '<tr>';
            echo '<td>' . ($i + 1) . '</td>';
            echo '<td>' . $x . '</td>';
            echo '<td>' . ($is_error ? '<span class="error">' . $f . '</span>' : $f) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
        
        // Статистика
        echo '<div class="statistics">';
        echo '<h3>Статистика</h3>';
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
        <p><strong>Тип верстки:</strong> D (Табличная верстка)</p>
        <p>&copy; 2025 Мерданов Худайназар, Группа 241-352</p>
    </footer>
</body>
</html>
