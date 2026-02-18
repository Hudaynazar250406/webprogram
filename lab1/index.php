<?php
// Настройки страницы
$student = 'Мерданов Худайназар, гр. 241-352';
$lab = 'Лабораторная работа А-1: Простейшая программа на PHP';
$page_title = $student . ' — ' . $lab . ' — Главная страница';
$current_page = 'index'; // для подсветки меню
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1><?php echo $lab; ?></h1>
    <nav>
        <!-- Пункт меню 1: Главная -->
        <a href="<?php
            // первый PHP‑фрагмент: задаём значения и выводим ссылку
            $name = 'Главная';
            $link = 'index.php';
            $is_current = ($current_page === 'index');
            echo $link;
        ?>" class="<?php
            // второй PHP‑фрагмент: формируем класс и при желании можно добавить ещё атрибуты
            if ($is_current) {
                echo 'selected_menu';
            } else {
                echo 'menu_item';
            }
        ?>"><?php echo $name; ?></a>

        <!-- Пункт меню 2: О студенте -->
        <a href="<?php
            $name = 'О студенте';
            $link = 'page2.php';
            $is_current = ($current_page === 'page2');
            echo $link;
        ?>" class="<?php
            if ($is_current) {
                echo 'selected_menu';
            } else {
                echo 'menu_item';
            }
        ?>"><?php echo $name; ?></a>

        <!-- Пункт меню 3: Учёба и проекты -->
        <a href="<?php
            $name = 'Учёба и проекты';
            $link = 'page3.php';
            $is_current = ($current_page === 'page3');
            echo $link;
        ?>" class="<?php
            if ($is_current) {
                echo 'selected_menu';
            } else {
                echo 'menu_item';
            }
        ?>"><?php echo $name; ?></a>
    </nav>
</header>

<main>
    <h1>Главная страница лабораторной работы</h1>

    <h2>Цель лабораторной работы</h2>
    <p>
        Здесь опиши своими словами цель работы: знакомство с основами PHP, 
        отличия статического и динамического контента, формирование HTML‑кода средствами PHP и т.д.
    </p>

    <h2>Краткое описание сайта</h2>
    <p>
        Напиши минимум 1 КБ текста: о себе, о теме сайта, почему выбрал эту тему, 
        какие страницы есть на сайте и что на них находится. Можно сделать 2–3 абзаца.
    </p>

    <!-- Динамическая фотография: меняется в зависимости от чётности секунды -->
    <h2>Динамическая фотография</h2>
    <p>При каждой перезагрузке страницы в зависимости от секунды будет подставляться разное фото.</p>
    <?php
    // Вариант из методички: foto1.jpg / foto2.jpg в папке fotos
    echo '<img src="fotos/foto' . (date('s') % 2 + 1) . '.jpg" alt="Меняющаяся фотография">';
    ?>

    <!-- Вторая (обычная) фотография для выполнения требования "не менее двух" -->
    <h2>Вторая фотография</h2>
    <img src="fotos/foto_static.jpg" alt="Статическая фотография">

    <!-- Таблица: первая строка полностью из PHP, во второй строке только содержимое ячеек -->
    <h2>Пример таблицы</h2>
    <table>
        <?php
        // Первая строка таблицы (заголовки) полностью формируется PHP
        echo '<tr><th>Параметр</th><th>Значение</th><th>Комментарий</th></tr>';
        ?>
        <tr>
            <td><?php echo 'Страница'; ?></td>
            <td><?php echo 'Главная'; ?></td>
            <td><?php echo 'Описание цели и структуры сайта'; ?></td>
        </tr>
    </table>
</main>

<footer>
    <?php
    // Строка с текущей датой и временем
    // Формат можешь подогнать под пример из методички
    echo 'Сформировано ' . date('d.m.Y в H-i:s');
    ?>
</footer>

</body>
</html>
