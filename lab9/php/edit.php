<?php
$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
    echo '<div class="msg-err">Ошибка подключения к БД: ' . mysqli_connect_error() . '</div>';
    exit();
}
mysqli_set_charset($mysqli, 'utf8mb4');

$msg = '';

// Обработка редактирования
if (isset($_POST['action']) && $_POST['action'] === 'edit_contact' && isset($_GET['id'])) {
    $id         = (int)$_GET['id'];
    $surname    = htmlspecialchars(trim($_POST['surname'] ?? ''));
    $firstname  = htmlspecialchars(trim($_POST['firstname'] ?? ''));
    $patronymic = htmlspecialchars(trim($_POST['patronymic'] ?? ''));
    $gender     = in_array($_POST['gender'] ?? '', ['М', 'Ж']) ? $_POST['gender'] : 'М';
    $birthdate  = $_POST['birthdate'] ?? '';
    $phone      = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $address    = htmlspecialchars(trim($_POST['address'] ?? ''));
    $email      = htmlspecialchars(trim($_POST['email'] ?? ''));
    $comment    = htmlspecialchars(trim($_POST['comment'] ?? ''));

    $stmt = $mysqli->prepare("UPDATE contacts SET surname=?, firstname=?, patronymic=?, gender=?, birthdate=?, phone=?, address=?, email=?, comment=? WHERE id=?");
    $stmt->bind_param('sssssssssi', $surname, $firstname, $patronymic, $gender, $birthdate, $phone, $address, $email, $comment, $id);
    if ($stmt->execute()) {
        $msg = '<div class="msg-ok">✓ Данные успешно изменены!</div>';
    } else {
        $msg = '<div class="msg-err">Ошибка при изменении записи.</div>';
    }
    $stmt->close();
}

// Определяем текущую запись
$currentROW = [];
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $res = mysqli_query($mysqli, "SELECT * FROM contacts WHERE id=$id LIMIT 1");
    if ($res) $currentROW = mysqli_fetch_assoc($res) ?: [];
}
if (!$currentROW) {
    $res = mysqli_query($mysqli, "SELECT * FROM contacts ORDER BY id ASC LIMIT 1");
    if ($res) $currentROW = mysqli_fetch_assoc($res) ?: [];
}

// Список всех записей
$res_list = mysqli_query($mysqli, "SELECT id, surname, firstname FROM contacts ORDER BY surname ASC, firstname ASC");
?>
<h2>Редактирование контакта</h2>
<?= $msg ?>
<div id="edit_links">
<?php
if ($res_list && mysqli_num_rows($res_list) > 0):
    while ($row = mysqli_fetch_assoc($res_list)):
        $name = htmlspecialchars($row['surname'] . ' ' . $row['firstname']);
        if ($currentROW && $currentROW['id'] == $row['id']): ?>
            <div class="current-item"><?= $name ?></div>
        <?php else: ?>
            <a href="/?p=edit&id=<?= $row['id'] ?>"><?= $name ?></a>
        <?php endif;
    endwhile;
else: ?>
    <span style="color:#9e9e9e">Список пуст</span>
<?php endif; ?>
</div>

<?php if ($currentROW): ?>
<form method="post" action="/?p=edit&id=<?= (int)$currentROW['id'] ?>">
    <input type="hidden" name="action" value="edit_contact">
    <div class="form-row-3">
        <div class="form-group">
            <label>Фамилия *</label>
            <input type="text" name="surname" value="<?= htmlspecialchars($currentROW['surname']) ?>" required>
        </div>
        <div class="form-group">
            <label>Имя *</label>
            <input type="text" name="firstname" value="<?= htmlspecialchars($currentROW['firstname']) ?>" required>
        </div>
        <div class="form-group">
            <label>Отчество</label>
            <input type="text" name="patronymic" value="<?= htmlspecialchars($currentROW['patronymic'] ?? '') ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Пол</label>
            <select name="gender">
                <option value="М" <?= ($currentROW['gender'] === 'М') ? 'selected' : '' ?>>Мужской</option>
                <option value="Ж" <?= ($currentROW['gender'] === 'Ж') ? 'selected' : '' ?>>Женский</option>
            </select>
        </div>
        <div class="form-group">
            <label>Дата рождения</label>
            <input type="date" name="birthdate" value="<?= htmlspecialchars($currentROW['birthdate'] ?? '') ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Телефон</label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($currentROW['phone'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" value="<?= htmlspecialchars($currentROW['email'] ?? '') ?>">
        </div>
    </div>
    <div class="form-group">
        <label>Адрес</label>
        <input type="text" name="address" value="<?= htmlspecialchars($currentROW['address'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label>Комментарий</label>
        <textarea name="comment"><?= htmlspecialchars($currentROW['comment'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
</form>
<?php else: ?>
    <p style="color:#9e9e9e">Записей пока нет. <a href="/?p=add">Добавьте первый контакт</a>.</p>
<?php endif; ?>
<?php mysqli_close($mysqli); ?>
