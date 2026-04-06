<?php
function getMenu() {
    $p = isset($_GET['p']) ? $_GET['p'] : 'viewer';
    $allowed = ['viewer', 'add', 'edit', 'delete'];
    if (!in_array($p, $allowed)) $p = 'viewer';

    $items = [
        'viewer' => 'Просмотр',
        'add'    => 'Добавление записи',
        'edit'   => 'Редактирование записи',
        'delete' => 'Удаление записи',
    ];

    $html = '<div id="menu">';
    foreach ($items as $key => $label) {
        $class = ($p === $key) ? ' class="selected"' : '';
        $html .= '<a href="/?p=' . $key . '"' . $class . '>' . $label . '</a>';
    }
    $html .= '</div>';

    // Подменю сортировки при просмотре
    if ($p === 'viewer') {
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'byid';
        $sorts = [
            'byid'  => 'По умолчанию',
            'fam'   => 'По фамилии',
            'birth' => 'По дате рождения',
        ];
        $html .= '<div id="submenu">';
        foreach ($sorts as $key => $label) {
            $class = ($sort === $key) ? ' class="selected"' : '';
            $html .= '<a href="/?p=viewer&sort=' . $key . '"' . $class . '>' . $label . '</a>';
        }
        $html .= '</div>';
    }

    return $html;
}
