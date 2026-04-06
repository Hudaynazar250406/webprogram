<?php
$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
    echo '<div class="msg-err">Ошибка подключения к БД: ' . mysqli_connect_error() . '</div>';
    exit();
}
mysqli_set_charset($mysqli, 'utf8mb4');

$msg = '';

// Удаление записи
if (isset($_GET['del_id'])) {
    $del_id = (int)$_GET['del_id'];
    $res_name = mysqli_query($mysqli, "SELECT surname FROM contacts WHERE id=$del_id LIMIT 1");
    if ($res_name && $row_name = mysqli_fetch_assoc($res_name)) {
        $del_surname = htmlspecialchars($row_name['surname']);
        $del_res = mysqli_query($mysqli, "DELETE FROM contacts WHERE id=$del_id");
        if ($del_res) {
            $msg = '<div class="msg-ok">✓ Запись с фамилией <strong>' . $del_surname . '</strong> удалена.</div>';
        } else {
            $msg = '<div class="msg-err">Ошибка при удалении записи.</div>';
        }
    } else {
        $msg = '<div class="msg-err">Запись не найдена.</div>';
    }
}

$res_list = mysqli_query($mysqli, "SELECT id, surname, firstname, patronymic FROM contacts ORDER BY surname ASC, firstname ASC");
?>
<h2>Удаление контакта</h2>
<?= $msg ?>
<?php if ($res_list && mysqli_num_rows($res_list) > 0): ?>
<p style="margin-bottom:16px;color:#757575;font-size:0.9rem;">Выберите контакт для удаления:</p>
<div id="delete_links">
<?php while ($row = mysqli_fetch_assoc($res_list)):
    $surname   = htmlspecialchars($row['surname']);
    $firstname = htmlspecialchars($row['firstname']);
    $patronymic = htmlspecialchars($row['patronymic'] ?? '');
    // Инициалы
    $initials = '';
    if ($firstname) $initials .= mb_strtoupper(mb_substr($firstname, 0, 1)) . '.';
    if ($patronymic) $initials .= ' ' . mb_strtoupper(mb_substr($patronymic, 0, 1)) . '.';
    $display = $surname . ($initials ? ' ' . trim($initials) : '');
?>
    <a href="/?p=delete&del_id=<?= $row['id'] ?>" onclick="return confirm('Удалить <?= $surname ?>?')">
        🗑 <?= $display ?>
    </a>
<?php endwhile; ?>
</div>
<?php else: ?>
    <div class="empty-state">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
        <p>Записей нет. <a href="/?p=add">Добавьте контакт</a>.</p>
    </div>
<?php endif; ?>
<?php mysqli_close($mysqli); ?>
