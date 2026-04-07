<?php
require_once __DIR__ . '/config.php';

function getFriendsList($type = 'byid', $page = 0)
{
    $mysqli = db_connect();

    $allowedSort = [
        'byid' => 'ORDER BY id ASC',
        'fam' => 'ORDER BY surname ASC, firstname ASC, patronymic ASC',
        'birth' => 'ORDER BY birthdate ASC, surname ASC, firstname ASC'
    ];
    $type = array_key_exists($type, $allowedSort) ? $type : 'byid';
    $order = $allowedSort[$type];

    $perPage = 10;
    $page = max(0, (int)$page);

    $countRes = mysqli_query($mysqli, 'SELECT COUNT(*) AS total FROM contacts');
    if (!$countRes) {
        return '<div class="content-card"><div class="error">Ошибка базы данных: не удалось получить количество записей.</div></div>';
    }

    $total = (int)mysqli_fetch_assoc($countRes)['total'];
    if ($total === 0) {
        return '<div class="content-card"><div class="empty">Записная книжка пуста. Добавьте первый контакт.</div></div>';
    }

    $pages = (int)ceil($total / $perPage);
    if ($page >= $pages) {
        $page = $pages - 1;
    }
    $offset = $page * $perPage;

    $sql = "SELECT * FROM contacts $order LIMIT $offset, $perPage";
    $res = mysqli_query($mysqli, $sql);
    if (!$res) {
        return '<div class="content-card"><div class="error">Ошибка базы данных: не удалось получить список контактов.</div></div>';
    }

    $html = '<div class="content-card">';
    $html .= '<table class="contacts-table">';
    $html .= '<thead><tr>';
    $html .= '<th>#</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Пол</th><th>Дата рождения</th><th>Телефон</th><th>Адрес</th><th>E-mail</th><th>Комментарий</th>';
    $html .= '</tr></thead><tbody>';

    $n = $offset + 1;
    while ($row = mysqli_fetch_assoc($res)) {
        $html .= '<tr>';
        $html .= '<td>' . $n++ . '</td>';
        $html .= '<td>' . htmlspecialchars($row['surname'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($row['firstname'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($row['patronymic'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($row['gender'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($row['birthdate'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($row['phone'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($row['address'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($row['email'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($row['comment'] ?? '') . '</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody></table>';

    if ($pages > 1) {
        $html .= '<div class="pagination">';
        for ($i = 0; $i < $pages; $i++) {
            if ($i === $page) {
                $html .= '<span class="page current">' . ($i + 1) . '</span>';
            } else {
                $html .= '<a class="page" href="?p=viewer&sort=' . urlencode($type) . '&pg=' . $i . '">' . ($i + 1) . '</a>';
            }
        }
        $html .= '</div>';
    }

    $html .= '</div>';
    mysqli_close($mysqli);
    return $html;
}
