<?php
$student = 'Мерданов Худайназар, гр. 241-352';
$lab = 'Лабораторная работа А-1: Простейшая программа на PHP';
$page_title = $student . ' — ' . $lab . ' — О студенте';
$current_page = 'page2';
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
        <!-- Такие же три пункта меню, как в index.php, только логика $current_page та же -->
        <a href="<?php
            $name = 'Главная';
            $link = 'index.php';
            $is_current = ($current_page === 'index');
            echo $link;
        ?>" class="<?php
            echo $is_current ? 'selected_menu' : 'menu_item';
        ?>"><?php echo $name; ?></a>

        <a href="<?php
            $name = 'О студенте';
            $link = 'page2.php';
            $is_current = ($current_page === 'page2');
            echo $link;
        ?>" class="<?php
            echo $is_current ? 'selected_menu' : 'menu_item';
        ?>"><?php echo $name; ?></a>

        <a href="<?php
            $name = 'Учёба и проекты';
            $link = 'page3.php';
            $is_current = ($current_page === 'page3');
            echo $link;
        ?>" class="<?php
            echo $is_current ? 'selected_menu' : 'menu_item';
        ?>"><?php echo $name; ?></a>
    </nav>
</header>

<main>
    <h1>О студенте</h1>

    <h2>Краткая информация</h2>
    <p>
        Здесь опиши кто ты, где учишься, какие интересы (инф. безопасность, backend, сети и т.п.).
    </p>

    <h2>Интересы и хобби</h2>
    <p>
        Набери минимум 1 КБ текста: любимые технологии, проекты, планы, почему выбрал ИБ и т.д.
    </p>

    <!-- Две фотографии (могут быть разные, здесь без динамики по секундам) -->
    <img src="fotos/foto_o_sebe1.jpg" alt="Фото 1">
    <img src="fotos/foto_o_sebe2.jpg" alt="Фото 2">

    <h2>Таблица навыков</h2>
    <table>
        <?php
        echo '<tr><th>Навык</th><th>Уровень</th><th>Комментарий</th></tr>';
        ?>
        <tr>
            <td><?php echo 'PHP'; ?></td>
            <td><?php echo 'Начальный'; ?></td>
            <td><?php echo 'Освоено на уровне базовой динамики страниц'; ?></td>
        </tr>
    </table>
</main>

<footer>
    <?php
    echo 'Сформировано ' . date('d.m.Y в H-i:s');
    ?>
</footer>

</body>
</html>
