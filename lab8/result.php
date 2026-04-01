<?php
// Лабораторная работа А-8 — Анализ текста
// Кодировка: UTF-8

// Перевод строки CP1251 в нижний регистр (кириллица + латиница)
function my_strtolower_cp1251( $str )
{
    $result = '';
    for ($i = 0; $i < strlen($str); $i++)
    {
        $code = ord($str[$i]);
        // Латинские заглавные A-Z: 65–90 → 97–122
        if ($code >= 65 && $code <= 90)
            $result .= chr($code + 32);
        // Кириллические заглавные А-Я (без Ё): 192–223 → 224–255
        elseif ($code >= 192 && $code <= 223)
            $result .= chr($code + 32);
        // Ё (168) → ё (184)
        elseif ($code == 168)
            $result .= chr(184);
        else
            $result .= $str[$i];
    }
    return $result;
}

// Функция подсчёта вхождений каждого символа (без различия регистров)
function test_symbs( $text )
{
    $symbs = array();
    $l_text = my_strtolower_cp1251( $text ); // переводим текст в нижний регистр

    for ($i = 0; $i < strlen($l_text); $i++)
    {
        if ( isset($symbs[$l_text[$i]]) )
            $symbs[$l_text[$i]]++;
        else
            $symbs[$l_text[$i]] = 1;
    }
    return $symbs;
}

// Основная функция анализа текста
function test_it( $text )
{
    // --- Количество символов (включая пробелы) ---
    $total_chars = strlen($text);

    // --- Массивы-справочники для классификации символов ---

    // Цифры
    $cifra = array(
        '0'=>true, '1'=>true, '2'=>true, '3'=>true, '4'=>true,
        '5'=>true, '6'=>true, '7'=>true, '8'=>true, '9'=>true
    );

    // Знаки препинания
    $punctuation = array(
        '.'=>true, ','=>true, '!'=>true, '?'=>true, ';'=>true,
        ':'=>true, '-'=>true, '('=>true, ')'=>true, '"'=>true,
        '\''=>true, '/'=>true, '\\'=>true
    );

    // Строчные буквы (латинские + кириллические в CP1251)
    $lower_letters = array();
    // Латинские строчные: a-z (коды 97–122)
    for ($c = 97; $c <= 122; $c++)
        $lower_letters[ chr($c) ] = true;
    // Кириллические строчные в CP1251: а-я (коды 224–255) + ё (184)
    for ($c = 224; $c <= 255; $c++)
        $lower_letters[ chr($c) ] = true;
    $lower_letters[ chr(184) ] = true; // ё

    // Заглавные буквы (латинские + кириллические в CP1251)
    $upper_letters = array();
    // Латинские заглавные: A-Z (коды 65–90)
    for ($c = 65; $c <= 90; $c++)
        $upper_letters[ chr($c) ] = true;
    // Кириллические заглавные в CP1251: А-Я (коды 192–223) + Ё (168)
    for ($c = 192; $c <= 223; $c++)
        $upper_letters[ chr($c) ] = true;
    $upper_letters[ chr(168) ] = true; // Ё

    // --- Переменные-счётчики ---
    $cifra_amount = 0;
    $punct_amount = 0;
    $lower_amount = 0;
    $upper_amount = 0;
    $letter_amount = 0;

    $word = '';
    $words = array();

    // --- Основной цикл по символам ---
    for ($i = 0; $i < strlen($text); $i++)
    {
        $ch = $text[$i];

        // Подсчёт цифр
        if ( array_key_exists($ch, $cifra) )
            $cifra_amount++;

        // Подсчёт знаков препинания
        if ( array_key_exists($ch, $punctuation) )
            $punct_amount++;

        // Подсчёт строчных букв
        if ( array_key_exists($ch, $lower_letters) )
        {
            $lower_amount++;
            $letter_amount++;
        }

        // Подсчёт заглавных букв
        if ( array_key_exists($ch, $upper_letters) )
        {
            $upper_amount++;
            $letter_amount++;
        }

        // --- Разбиение на слова ---
        // Признак окончания слова: пробел, перевод строки, знак препинания
        $is_separator = ($ch == ' ' || $ch == "\n" || $ch == "\r" || $ch == "\t"
                         || array_key_exists($ch, $punctuation));
        $is_last = ($i == strlen($text) - 1);

        if ($is_separator || $is_last)
        {
            // Если последний символ — часть слова, добавляем его
            if ($is_last && !$is_separator)
                $word .= $ch;

            if ($word)
            {
                // Приводим слово к нижнему регистру для учёта без различия регистров
                $word_lower = my_strtolower_cp1251($word);
                if ( isset($words[$word_lower]) )
                    $words[$word_lower]++;
                else
                    $words[$word_lower] = 1;
            }
            $word = '';
        }
        else
        {
            $word .= $ch;
        }
    }

    // Сортировка слов по алфавиту (по ключам)
    ksort($words);

    // --- Подсчёт вхождений символов ---
    $symbs = test_symbs($text);

    // --- Вывод результатов в виде таблицы ---
    echo '<h2>Результат анализа</h2>';
    echo '<table>';
    echo '<tr><th>Параметр</th><th>Значение</th></tr>';
    echo '<tr><td>Количество символов (включая пробелы)</td><td>'.$total_chars.'</td></tr>';
    echo '<tr><td>Количество букв</td><td>'.$letter_amount.'</td></tr>';
    echo '<tr><td>Количество строчных букв</td><td>'.$lower_amount.'</td></tr>';
    echo '<tr><td>Количество заглавных букв</td><td>'.$upper_amount.'</td></tr>';
    echo '<tr><td>Количество знаков препинания</td><td>'.$punct_amount.'</td></tr>';
    echo '<tr><td>Количество цифр</td><td>'.$cifra_amount.'</td></tr>';
    echo '<tr><td>Количество слов</td><td>'.count($words).'</td></tr>';
    echo '</table>';

    // --- Таблица вхождений символов ---
    echo '<h2>Вхождения символов</h2>';
    echo '<table>';
    echo '<tr><th>Символ</th><th>Количество</th></tr>';
    foreach ($symbs as $key => $value)
    {
        $display_key = iconv("cp1251", "utf-8", $key);
        // Отображаем спецсимволы наглядно
        if ($key == ' ')
            $display_key = '(пробел)';
        elseif ($key == "\n" || $key == "\r")
            $display_key = '(перевод строки)';
        elseif ($key == "\t")
            $display_key = '(табуляция)';
        echo '<tr><td>'.htmlspecialchars($display_key).'</td><td>'.$value.'</td></tr>';
    }
    echo '</table>';

    // --- Таблица слов ---
    echo '<h2>Слова в тексте</h2>';
    echo '<table>';
    echo '<tr><th>Слово</th><th>Количество вхождений</th></tr>';
    foreach ($words as $key => $value)
    {
        $display_word = iconv("cp1251", "utf-8", $key);
        echo '<tr><td>'.htmlspecialchars($display_word).'</td><td>'.$value.'</td></tr>';
    }
    echo '</table>';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результат анализа — Лабораторная работа А-8</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px auto;
            max-width: 800px;
            background: #f5f5f5;
            color: #333;
        }
        h1 {
            text-align: center;
            font-size: 22px;
        }
        h2 {
            font-size: 18px;
            margin-top: 24px;
        }
        .src_text {
            color: #1a5276;
            font-style: italic;
            background: #eaf2f8;
            padding: 12px;
            border: 1px solid #aed6f1;
            border-radius: 4px;
            white-space: pre-wrap;
            margin-bottom: 16px;
        }
        .src_error {
            color: #c0392b;
            font-weight: bold;
            font-size: 16px;
            padding: 12px;
            background: #fdedec;
            border: 1px solid #f5b7b1;
            border-radius: 4px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 16px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px 10px;
            text-align: left;
        }
        th {
            background: #ddd;
        }
        a.btn {
            display: inline-block;
            margin-top: 16px;
            padding: 10px 24px;
            font-size: 15px;
            background: #2a7ae2;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        a.btn:hover {
            background: #1b5eb5;
        }
    </style>
</head>
<body>
    <h1>Результат анализа текста</h1>
<?php
if ( isset($_POST['data']) && $_POST['data'] )
{
    // Выводим исходный текст (цветом и курсивом)
    echo '<div class="src_text">'.htmlspecialchars($_POST['data']).'</div>';

    // Перекодируем текст из UTF-8 в CP1251 для корректной побайтовой обработки
    test_it( iconv("utf-8", "cp1251", $_POST['data']) );
}
else
{
    echo '<div class="src_error">Нет текста для анализа</div>';
}
?>
    <a class="btn" href="index.html">Другой анализ</a>
</body>
</html>
