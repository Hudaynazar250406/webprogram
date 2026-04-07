<?php
require_once __DIR__ . '/config.php';
$mysqli = db_connect();
$msg = '';
$msgClass = 'ok';

if (isset($_GET['del_id'])) {
    $delId = (int)$_GET['del_id'];
    $stmt = $mysqli->prepare('SELECT surname, firstname, patronymic FROM contacts WHERE id=? LIMIT 1');
    $stmt->bind_param('i', $delId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if ($row) {
        $surname = $row['surname'];
        $stmt = $mysqli->prepare('DELETE FROM contacts WHERE id=?');
        $stmt->bind_param('i', $delId);
        if ($stmt->execute()) {
            $msg = 'Запись с фамилией ' . $surname . ' удалена.';
        } else {
            $msg = 'Ошибка: запись не удалена.';
            $msgClass = 'error';
        }
        $stmt->close();
    } else {
        $msg = 'Запись не найдена.';
        $msgClass = 'error';
    }
}

$res = mysqli_query($mysqli, 'SELECT id, surname, firstname, patronymic FROM contacts ORDER BY surname ASC, firstname ASC, patronymic ASC');
?>
<div class="content-card form-card">
    <h2>Удаление записи</h2>
    <?php if ($msg): ?>
        <div class="<?= $msgClass ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <?php if (!$res || mysqli_num_rows($res) === 0): ?>
        <div class="empty">Записей нет. Добавьте контакт.</div>
    <?php else: ?>
        <div class="delete-links">
            <?php while ($row = mysqli_fetch_assoc($res)): ?>
                <?php
                    $initials = '';
                    if (!empty($row['firstname'])) $initials .= ' ' . mb_substr($row['firstname'], 0, 1, 'UTF-8') . '.';
                    if (!empty($row['patronymic'])) $initials .= mb_substr($row['patronymic'], 0, 1, 'UTF-8') . '.';
                ?>
                <a class="delete-link" href="?p=delete&del_id=<?= (int)$row['id'] ?>" onclick="return confirm('Удалить запись?');">
                    <?= htmlspecialchars($row['surname'] . $initials) ?>
                </a>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>
<?php mysqli_close($mysqli); ?>
