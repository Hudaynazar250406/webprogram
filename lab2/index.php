<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Лабораторная работа по PHP - Табулирование функций">
    <title>Мерданов Худайназар | Группа 241-352 | Лабораторная работа № А-2 | Вариант 16</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <!-- Логотип университета -->
            <img src="https://via.placeholder.com/150x80/ffffff/2c3e50?text=LOGO" alt="Логотип университета">
        </div>
        <h1>Лабораторная работа № А-2</h1>
        <div class="student-info">
            <p><strong>Выполнил:</strong> Мерданов Худайназар</p>
            <p><strong>Группа:</strong> 241-352</p>
            <p><strong>Вариант:</strong> 16</p>
            <p><strong>Тема:</strong> Циклические алгоритмы. Условия в алгоритмах. Табулирование функций.</p>
        </div>
    </header>

    <main>
        <h2>Результаты табулирования функции</h2>
        
        <?php
        // ============================================
        // Инициализация переменных
        // ============================================
        
        // Начальное значение аргумента
        $start_value = -10;
        
        // Количество вычисляемых значений
        $encounting = 30;
        
        // Шаг изменения аргумента
        $step = 1;
        
        // Минимальное значение функции (остановка при достижении)
        $min_value = -50;
        
        // Максимальное значение функции (остановка при достижении)
        $max_value = 100;
        
        // Тип формируемой верстки: 'A', 'B', 'C', 'D', 'E'
        $type = 'A';
        
        // Текущее значение аргумента
        $x = $start_value;
        
        // Массив для хранения всех значений функции (для статистики)
        $values = array();
        
        // Счетчик фактически выполненных итераций
        $actual_count = 0;
        
        // ============================================
        // Функция для вычисления значения (Вариант 16)
        // ============================================
        // f(x) = x^2 / 10                 при x <= 0
        // f(x) = (x + 5) / (x - 10)       при 0 < x < 15  (проверка деления на 0 при x=10)
        // f(x) = sqrt(x - 14) + 2         при x >= 15
        // ============================================

        function calculateFunction($x) {
            if ($x <= 10) {
                // f(x) = x² · 0.33 + 4
                $f = $x * $x * 0.33 + 4;

            } elseif ($x < 20) {
                // f(x) = 18·x - 3
                $f = 18 * $x - 3;

            } else {
                // f(x) = 1 / (x·0.1 - 2) + 3
                if ($x * 0.1 - 2 == 0) { // только при x = 20
                    return 'error';
                }
                $f = 1 / ($x * 0.1 - 2) + 3;
            }

            return round($f, 3);
        }


        // ============================================
        // Вывод начала списков (для типов B и C)
        // ============================================
        
        if ($type == 'B') {
            echo '<ul class="output-list">';
        } elseif ($type == 'C') {
            echo '<ol class="output-list">';
        } elseif ($type == 'E') {
            echo '<div class="output-blocks">';
        } elseif ($type == 'D') {
            echo '<table class="output-table">';
            echo '<thead><tr><th>№</th><th>Аргумент (x)</th><th>Значение f(x)</th></tr></thead>';
            echo '<tbody>';
        }
        
        // ============================================
        // Основной цикл вычислений (цикл со счетчиком for)
        // ============================================
        
        for ($i = 0; $i < $encounting; $i++, $x += $step) {
            // Вычисление значения функции
            $f = calculateFunction($x);
            
            // Проверка на ошибку
            $is_error = ($f === 'error');
            
            // Если нет ошибки, проверяем ограничения min/max
            if (!$is_error) {
                // Проверка на выход за пределы допустимого диапазона
                if ($f >= $max_value || $f < $min_value) {
                    break; // Досрочное завершение цикла
                }
                // Сохраняем значение для статистики
                $values[] = $f;
            }
            
            $actual_count++;
            
            // ============================================
            // Вывод в зависимости от типа верстки
            // ============================================
            
            switch ($type) {
                case 'A':
                    // Простая верстка текстом (тип A)
                    echo '<div class="output-text">';
                    echo 'f(' . $x . ')=' . $f;
                    echo '</div>';
                    if ($i < $encounting - 1) {
                        echo '<br>';
                    }
                    break;
                    
                case 'B':
                    // Маркированный список (тип B)
                    echo '<li>f(' . $x . ')=' . $f . '</li>';
                    break;
                    
                case 'C':
                    // Нумерованный список (тип C)
                    echo '<li>f(' . $x . ')=' . $f . '</li>';
                    break;
                    
                case 'D':
                    // Табличная верстка (тип D)
                    echo '<tr>';
                    echo '<td>' . ($i + 1) . '</td>';
                    echo '<td>' . $x . '</td>';
                    echo '<td>' . ($is_error ? '<span class="error">' . $f . '</span>' : $f) . '</td>';
                    echo '</tr>';
                    break;
                    
                case 'E':
                    // Блочная верстка (тип E)
                    echo '<div class="block-item">f(' . $x . ')=' . $f . '</div>';
                    break;
            }
        }
        
        // ============================================
        // Закрытие тегов списков/таблиц
        // ============================================
        
        if ($type == 'B') {
            echo '</ul>';
        } elseif ($type == 'C') {
            echo '</ol>';
        } elseif ($type == 'E') {
            echo '</div>';
            // Очистка float
            echo '<div style="clear: both;"></div>';
        } elseif ($type == 'D') {
            echo '</tbody></table>';
        }
        
        // ============================================
        // Вычисление статистики
        // ============================================
        
        echo '<div class="statistics">';
        echo '<h3>Статистические данные</h3>';
        
        if (count($values) > 0) {
            // Максимальное значение
            $max_f = max($values);
            
            // Минимальное значение
            $min_f = min($values);
            
            // Среднее арифметическое
            $avg_f = round(array_sum($values) / count($values), 3);
            
            // Сумма всех значений
            $sum_f = round(array_sum($values), 3);
            
            echo '<p><strong>Максимальное значение:</strong> ' . $max_f . '</p>';
            echo '<p><strong>Минимальное значение:</strong> ' . $min_f . '</p>';
            echo '<p><strong>Среднее арифметическое:</strong> ' . $avg_f . '</p>';
            echo '<p><strong>Сумма всех значений:</strong> ' . $sum_f . '</p>';
            echo '<p><strong>Количество вычисленных значений:</strong> ' . count($values) . '</p>';
        } else {
            echo '<p>Нет данных для статистики (все значения вышли за пределы допустимого диапазона)</p>';
        }
        
        echo '</div>';
        
        // ============================================
        // Информация о параметрах
        // ============================================
        
        echo '<div class="statistics">';
        echo '<h3>Параметры вычислений</h3>';
        echo '<p><strong>Начальное значение аргумента:</strong> ' . $start_value . '</p>';
        echo '<p><strong>Шаг изменения аргумента:</strong> ' . $step . '</p>';
        echo '<p><strong>Запланированное количество итераций:</strong> ' . $encounting . '</p>';
        echo '<p><strong>Фактическое количество итераций:</strong> ' . $actual_count . '</p>';
        echo '<p><strong>Минимальное ограничение:</strong> ' . $min_value . '</p>';
        echo '<p><strong>Максимальное ограничение:</strong> ' . $max_value . '</p>';
        echo '</div>';
        ?>
        
    </main>

    <footer>
        <?php
        // Вывод типа верстки в подвале
        $type_description = '';
        switch ($type) {
            case 'A':
                $type_description = 'A (Простая текстовая верстка)';
                break;
            case 'B':
                $type_description = 'B (Маркированный список)';
                break;
            case 'C':
                $type_description = 'C (Нумерованный список)';
                break;
            case 'D':
                $type_description = 'D (Табличная верстка)';
                break;
            case 'E':
                $type_description = 'E (Блочная верстка)';
                break;
            default:
                $type_description = 'Неизвестный тип';
        }
        ?>
        <p><strong>Тип верстки:</strong> <?php echo $type_description; ?></p>
        <p>&copy; 2025 Мерданов Худайназар, Группа 241-352</p>
    </footer>
</body>
</html>
