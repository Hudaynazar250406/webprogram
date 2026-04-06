<?php
function getFriendsList($type, $page) {
    $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (mysqli_connect_errno()) {
        return '<div class="msg-err">Ошибка подключения к БД: ' . mysqli_connect_error() . '</div>';
    }
    mysqli_set_charset($mysqli, 'utf8mb4');

    // Определяем сортировку
    $order = 'ORDER BY id ASC';
    $sort_param = '';
    if ($type === 'fam') {
        $order = 'ORDER BY surname ASC, firstname ASC';
        $sort_param = '&sort=fam';
    } elseif ($type === 'birth') {
        $order = 'ORDER BY birthdate ASC';
        $sort_param = '&sort=birth';
    }

    // Считаем общее кол-во записей
    $res_count = mysqli_query($mysqli, 'SELECT COUNT(*) FROM contacts');
    if (!$res_count) {
        return '<div class="msg-err">Ошибка базы данных</div>';
    }
    $row_count = mysqli_fetch_row($res_count);
    $total = (int)$row_count[0];

    if ($total === 0) {
        return '<h2>Просмотр контактов</h2><div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <p>Записная книжка пуста. Добавьте первый контакт!</p>
        </div>';
    }

    $pages = (int)ceil($total / 10);
    if ($page >= $pages) $page = $pages - 1;
    if ($page < 0) $page = 0;

    $offset = $page * 10;
    $sql = "SELECT * FROM contacts $order LIMIT $offset, 10";
    $res = mysqli_query($mysqli, $sql);

    $ret = '<h2>Просмотр контактов <small style="font-size:0.85rem;color:#9e9e9e;font-weight:400;">Всего: ' . $total . '</small></h2>';
    $ret .= '<div style="overflow-x:auto"><table>';
    $ret .= '<tr>
        <th>#</th>
        <th>Фамилия</th><th>Имя</th><th>Отчество</th>
        <th>Пол</th><th>Дата рождения</th>
        <th>Телефон</th><th>Адрес</th><th>E-mail</th><th>Комментарий</th>
    </tr>';

    $n = $offset + 1;
    while ($row = mysqli_fetch_assoc($res)) {
        $gender_badge = $row['gender'] === 'М'
            ? '<span class="badge-m">М</span>'
            : '<span class="badge-f">Ж</span>';
        $ret .= '<tr>
            <td>' . $n++ . '</td>
            <td><strong>' . htmlspecialchars($row['surname']) . '</strong></td>
            <td>' . htmlspecialchars($row['firstname']) . '</td>
            <td>' . htmlspecialchars($row['patronymic']) . '</td>
            <td>' . $gender_badge . '</td>
            <td>' . htmlspecialchars($row['birthdate'] ?? '') . '</td>
            <td>' . htmlspecialchars($row['phone'] ?? '') . '</td>
            <td>' . htmlspecialchars($row['address'] ?? '') . '</td>
            <td><a href="mailto:' . htmlspecialchars($row['email'] ?? '') . '">' . htmlspecialchars($row['email'] ?? '') . '</a></td>
            <td>' . htmlspecialchars($row['comment'] ?? '') . '</td>
        </tr>';
    }
    $ret .= '</table></div>';

    if ($pages > 1) {
        $ret .= '<div class="pagination">';
        for ($i = 0; $i < $pages; $i++) {
            if ($i === $page) {
                $ret .= '<span>' . ($i + 1) . '</span>';
            } else {
                $ret .= '<a href="/?p=viewer&pg=' . $i . $sort_param . '">' . ($i + 1) . '</a>';
            }
        }
        $ret .= '</div>';
    }

    mysqli_close($mysqli);
    return $ret;
}
