<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мерданов Худайназар | Группа 241-352 | Лабораторная № А-2 | Тип E</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="https://via.placeholder.com/150x80/ffffff/2c3e50?text=LOGO" alt="Логотип университета">
        </div>
        <h1>Лабораторная работа № А-2 (Тип верстки E)</h1>
        <div class="student-info">
            <p><strong>Выполнил:</strong> Мерданов Худайназар</p>
            <p><strong>Группа:</strong> 241-352</p>
            <p><strong>Вариант:</strong> 16</p>
        </div>
    </header>

    <main>
        <h2>Результаты табулирования функции (Блочная верстка)</h2>
        
        <?php
        $start_value = -5;
        $encounting = 15;
        $step = 1;
        $min_value = -100;
        $max_value = 100;
        $type = 'E';
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
        
        // Тип E: блочная верстка
        echo '<div class="output-blocks">';
        
        for ($i = 0; $i < $encounting; $i++, $x += $step) {
            $f = calculateFunction($x);
            if ($f !== 'error') {
                if ($f >= $max_value || $f < $min_value) break;
                $values[] = $f;
            }
            
            echo '<div class="block-item">f(' . $x . ')=' . $f . '</div>';
        }
        
        echo '</div>';
        echo '<div style="clear: both;"></div>';
        
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
        <p><strong>Тип верстки:</strong> E (Блочная верстка)</p>
        <p>&copy; 2025 Мерданов Худайназар, Группа 241-352</p>
    </footer>
</body>
</html>
