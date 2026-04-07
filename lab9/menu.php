<?php
function renderMenu() {
    $allowedPages = ['viewer', 'add', 'edit', 'delete'];
    $p = $_GET['p'] ?? 'viewer';
    if (!in_array($p, $allowedPages, true)) {
        $p = 'viewer';
    }

    $main = [
        'viewer' => 'Просмотр',
        'add' => 'Добавление записи',
        'edit' => 'Редактирование записи',
        'delete' => 'Удаление записи'
    ];

    $html = '<div class="menu">';
    foreach ($main as $key => $title) {
        $class = $p === $key ? 'menu-link active' : 'menu-link';
        $html .= '<a class="' . $class . '" href="?p=' . $key . '">' . $title . '</a>';
    }
    $html .= '</div>';

    if ($p === 'viewer') {
        $sort = $_GET['sort'] ?? 'byid';
        $sorts = [
            'byid' => 'По умолчанию',
            'fam' => 'По фамилии',
            'birth' => 'По дате рождения'
        ];
        $html .= '<div class="submenu">';
        foreach ($sorts as $key => $title) {
            $class = $sort === $key ? 'submenu-link active' : 'submenu-link';
            $html .= '<a class="' . $class . '" href="?p=viewer&sort=' . $key . '">' . $title . '</a>';
        }
        $html .= '</div>';
    }

    return $html;
}
