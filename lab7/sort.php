<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результат сортировки</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2196F3;
        }
        .arr_element {
            display: inline-block;
            background: #e3f2fd;
            border: 1px solid #90caf9;
            border-radius: 4px;
            padding: 4px 10px;
            margin: 2px;
            font-weight: bold;
        }
        .iteration {
            margin: 8px 0;
            padding: 8px;
            background: #fafafa;
            border-left: 3px solid #4CAF50;
            font-size: 13px;
        }
        .iteration .iter-num {
            font-weight: bold;
            color: #333;
        }
        .warning {
            color: #d32f2f;
            font-weight: bold;
            padding: 10px;
            background: #ffebee;
            border-radius: 4px;
        }
        .success {
            color: #388e3c;
            font-weight: bold;
            padding: 10px;
            background: #e8f5e9;
            border-radius: 4px;
            margin: 10px 0;
        }
        .result {
            color: #1565c0;
            font-weight: bold;
            padding: 10px;
            background: #e3f2fd;
            border-radius: 4px;
            margin: 10px 0;
        }
        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 15px 0;
        }
    </style>
</head>
<body>
<div class="container">

<?php

// ============================================================
// Функция проверки: является ли аргумент числом
// ============================================================
function arg_is_not_Num( $arg )
{
    if( $arg === '' ) return true; // передана пустая строка

    // Допускаем отрицательные числа и дробные (с точкой)
    // Но по заданию — только целые числа (цифры и минус в начале)
    $start = 0;
    if( $arg[0] === '-' )
    {
        if( strlen($arg) === 1 ) return true; // только минус
        $start = 1;
    }

    for($i=$start; $i<strlen($arg); $i++)
        if( $arg[$i]!=='0' && $arg[$i]!=='1' && $arg[$i]!=='2' &&
            $arg[$i]!=='3' && $arg[$i]!=='4' && $arg[$i]!=='5' &&
            $arg[$i]!=='6' && $arg[$i]!=='7' && $arg[$i]!=='8' &&
            $arg[$i]!=='9' )
            return true;

    return false;
}

// ============================================================
// Вспомогательная функция вывода массива
// ============================================================
function printArray( $arr )
{
    $out = '';
    for($i=0; $i<count($arr); $i++)
        $out .= '<div class="arr_element">'.$i.': '.$arr[$i].'</div>';
    return $out;
}

// ============================================================
// 1. Сортировка выбором
// ============================================================
function sorting_by_choice( &$arr )
{
    $n = 0;
    for($i=0; $i<count($arr)-1; $i++)
    {
        $min = $i;
        for($j=$i+1; $j<count($arr); $j++)
        {
            $n++;
            if( $arr[$j] < $arr[$min] ) $min = $j;
        }

        if( $min > $i )
        {
            $temp = $arr[$i];
            $arr[$i] = $arr[$min];
            $arr[$min] = $temp;
        }

        echo '<div class="iteration"><span class="iter-num">Итерация '.$n.':</span> '.printArray($arr).'</div>';
    }
    return $n;
}

// ============================================================
// 2. Пузырьковая сортировка
// ============================================================
function BubbleSort( &$arr )
{
    $n = 0;
    for($j=0; $j<count($arr)-1; $j++)
    {
        for($i=0; $i<count($arr)-1-$j; $i++)
        {
            $n++;
            if( $arr[$i] > $arr[$i+1] )
            {
                $temp = $arr[$i];
                $arr[$i] = $arr[$i+1];
                $arr[$i+1] = $temp;
            }
        }
        echo '<div class="iteration"><span class="iter-num">Итерация '.$n.':</span> '.printArray($arr).'</div>';
    }
    return $n;
}

// ============================================================
// 3. Алгоритм Шелла
// ============================================================
function ShellsSort( &$arr )
{
    $n = 0;
    for( $k = intval(count($arr)/2); $k >= 1; $k = intval($k/2) )
    {
        for($i=$k; $i<count($arr); $i++)
        {
            $val = $arr[$i];
            $j = $i - $k;

            while( $j >= 0 && $arr[$j] > $val )
            {
                $n++;
                $arr[$j+$k] = $arr[$j];
                $j -= $k;
            }
            $arr[$j+$k] = $val;
            $n++;
        }
        echo '<div class="iteration"><span class="iter-num">Итерация '.$n.' (шаг='.$k.'):</span> '.printArray($arr).'</div>';
    }
    return $n;
}

// ============================================================
// 4. Алгоритм садового гнома (оптимизированный)
// ============================================================
function gnomeSort( &$arr )
{
    $n = 0;
    $i = 1;
    $j = 2;
    while( $i < count($arr) )
    {
        $n++;
        if( !$i || $arr[$i-1] <= $arr[$i] )
        {
            $i = $j;
            $j++;
        }
        else
        {
            $temp = $arr[$i];
            $arr[$i] = $arr[$i-1];
            $arr[$i-1] = $temp;
            $i--;
        }
        echo '<div class="iteration"><span class="iter-num">Итерация '.$n.':</span> '.printArray($arr).'</div>';
    }
    return $n;
}

// ============================================================
// 5. Быстрая сортировка
// ============================================================
$quickSortIterations = 0;

function quickSort( &$arr, $left, $right )
{
    global $quickSortIterations;

    $l = $left;
    $r = $right;
    $point = $arr[ intval(($left + $right) / 2) ];

    do
    {
        while( $arr[$l] < $point ) $l++;
        while( $arr[$r] > $point ) $r--;

        if( $l <= $r )
        {
            $temp = $arr[$l];
            $arr[$l] = $arr[$r];
            $arr[$r] = $temp;
            $l++;
            $r--;
        }

        $quickSortIterations++;
        echo '<div class="iteration"><span class="iter-num">Итерация '.$quickSortIterations.':</span> '.printArray($arr).'</div>';

    } while( $l <= $r );

    if( $r > $left )
        quickSort($arr, $left, $r);
    if( $l < $right )
        quickSort($arr, $l, $right);
}

function quickSortWrapper( &$arr )
{
    global $quickSortIterations;
    $quickSortIterations = 0;

    if( count($arr) > 1 )
        quickSort($arr, 0, count($arr)-1);

    return $quickSortIterations;
}

// ============================================================
// 6. Встроенная функция PHP sort()
// ============================================================
function builtinSort( &$arr )
{
    sort($arr, SORT_NUMERIC);
    echo '<div class="iteration"><span class="iter-num">Результат:</span> '.printArray($arr).'</div>';
    return 0; // количество итераций неизвестно для встроенной функции
}

// ============================================================
// ОСНОВНАЯ ПРОГРАММА
// ============================================================

// Названия алгоритмов
$algorithmNames = array(
    '0' => 'Сортировка выбором',
    '1' => 'Пузырьковый алгоритм',
    '2' => 'Алгоритм Шелла',
    '3' => 'Алгоритм садового гнома',
    '4' => 'Быстрая сортировка',
    '5' => 'Встроенная функция PHP (sort)'
);

// 1. Проверка наличия данных
if( !isset($_POST['element0']) )
{
    echo '<div class="warning">Массив не задан, сортировка невозможна.</div>';
    echo '</div></body></html>';
    exit();
}

$arrLength = intval($_POST['arrLength']);

// 2. Валидация: все ли элементы числа
for($i=0; $i<$arrLength; $i++)
{
    if( !isset($_POST['element'.$i]) || arg_is_not_Num( $_POST['element'.$i] ) )
    {
        echo '<div class="warning">Элемент массива "'.(isset($_POST['element'.$i]) ? $_POST['element'.$i] : '').'" (индекс '.$i.') – не число. Сортировка невозможна.</div>';
        echo '</div></body></html>';
        exit();
    }
}

// 3. Определяем алгоритм
$algId = $_POST['algoritm'];
$algName = isset($algorithmNames[$algId]) ? $algorithmNames[$algId] : 'Неизвестный алгоритм';

echo '<h1>'.$algName.'</h1>';

// 4. Формируем массив и выводим исходные данные
$arr = array();
echo '<h3>Исходный массив:</h3>';
for($i=0; $i<$arrLength; $i++)
{
    $arr[] = intval($_POST['element'.$i]);
}
echo printArray($arr);

// 5. Сообщение об успешной валидации
echo '<div class="success">Массив проверен, все элементы — числа. Сортировка возможна.</div>';
echo '<hr>';
echo '<h3>Ход сортировки:</h3>';

// 6. Засекаем время и запускаем сортировку
$time = microtime(true);

switch($algId)
{
    case '0':
        $n = sorting_by_choice($arr);
        break;
    case '1':
        $n = BubbleSort($arr);
        break;
    case '2':
        $n = ShellsSort($arr);
        break;
    case '3':
        $n = gnomeSort($arr);
        break;
    case '4':
        $n = quickSortWrapper($arr);
        break;
    case '5':
        $n = builtinSort($arr);
        break;
    default:
        echo '<div class="warning">Неизвестный алгоритм.</div>';
        echo '</div></body></html>';
        exit();
}

$elapsed = microtime(true) - $time;

// 7. Итоговое сообщение
echo '<hr>';
echo '<h3>Отсортированный массив:</h3>';
echo printArray($arr);

echo '<div class="result">';
if($algId === '5')
    echo 'Сортировка завершена (встроенная функция, количество итераций недоступно). ';
else
    echo 'Сортировка завершена, проведено '.$n.' итераций. ';
echo 'Сортировка заняла '.number_format($elapsed, 6).' секунд.</div>';

?>

</div>
</body>
</html>
