<?php
require_once __DIR__ . '/config.php';
$mysqli = db_connect();
$msg = '';
$msgClass = 'ok';

$listRes = mysqli_query($mysqli, 'SELECT id, surname, firstname, patronymic FROM contacts ORDER BY surname ASC, firstname ASC, patronymic ASC');
$contacts = [];
while ($listRes && $row = mysqli_fetch_assoc($listRes)) {
    $contacts[] = $row;
}

$currentId = isset($_GET['id']) ? (int)$_GET['id'] : ($contacts[0]['id'] ?? 0);

if (isset($_POST['action']) && $_POST['action'] === 'edit_contact' && isset($_GET['id'])) {
    $currentId  = (int)$_GET['id'];
    $surname    = trim($_POST['surname']    ?? '');
    $firstname  = trim($_POST['firstname']  ?? '');
    $patronymic = trim($_POST['patronymic'] ?? '');
    $gender     = in_array($_POST['gender'] ?? '', ['М', 'Ж']) ? $_POST['gender'] : 'М';
    $birthdate  = trim($_POST['birthdate']  ?? '');
    $phone      = trim($_POST['phone']      ?? '');
    $address    = trim($_POST['address']    ?? '');
    $email      = trim($_POST['email']      ?? '');
    $comment    = trim($_POST['comment']    ?? '');

    // Пустую дату передаём как NULL, иначе MySQL ругается
    if ($birthdate === '') {
        $birthdate = null;
    }

    if ($surname === '' || $firstname === '') {
        $msg      = 'Ошибка: заполните фамилию и имя.';
        $msgClass = 'error';
    } else {
        $stmt = $mysqli->prepare('UPDATE contacts SET surname=?, firstname=?, patronymic=?, gender=?, birthdate=?, phone=?, address=?, email=?, comment=? WHERE id=?');
        $stmt->bind_param('sssssssssi', $surname, $firstname, $patronymic, $gender, $birthdate, $phone, $address, $email, $comment, $currentId);
        if ($stmt->execute()) {
            $msg = 'Запись изменена.';
            // Обновим список после изменения
            $listRes2 = mysqli_query($mysqli, 'SELECT id, surname, firstname, patronymic FROM contacts ORDER BY surname ASC, firstname ASC, patronymic ASC');
            $contacts = [];
            while ($listRes2 && $row2 = mysqli_fetch_assoc($listRes2)) {
                $contacts[] = $row2;
            }
        } else {
            $msg      = 'Ошибка: запись не изменена. ' . htmlspecialchars($stmt->error);
            $msgClass = 'error';
        }
        $stmt->close();
    }
}

$current = null;
if ($currentId > 0) {
    $stmt = $mysqli->prepare('SELECT * FROM contacts WHERE id=? LIMIT 1');
    $stmt->bind_param('i', $currentId);
    $stmt->execute();
    $result  = $stmt->get_result();
    $current = $result->fetch_assoc();
    $stmt->close();
}
?>
<div class="content-card form-card">
    <h2>Редактирование записи</h2>
    <?php if ($msg): ?>
        <div class="<?= $msgClass ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <?php if (!$contacts): ?>
        <div class="empty">Записей пока нет. Добавьте первый контакт.</div>
    <?php else: ?>
        <div class="edit-links">
            <?php foreach ($contacts as $item): ?>
                <?php $label = trim($item['surname'] . ' ' . $item['firstname']); ?>
                <?php if ((int)$item['id'] === (int)$currentId): ?>
                    <span class="selected-link"><?= htmlspecialchars($label) ?></span>
                <?php else: ?>
                    <a href="?p=edit&id=<?= (int)$item['id'] ?>"><?= htmlspecialchars($label) ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <?php if ($current): ?>
            <form method="post" action="?p=edit&id=<?= (int)$currentId ?>" class="contact-form">
                <input type="hidden" name="action" value="edit_contact">
                <input type="text"  name="surname"    placeholder="Фамилия" value="<?= htmlspecialchars($current['surname']    ?? '') ?>" required>
                <input type="text"  name="firstname"  placeholder="Имя"     value="<?= htmlspecialchars($current['firstname']  ?? '') ?>" required>
                <input type="text"  name="patronymic" placeholder="Отчество" value="<?= htmlspecialchars($current['patronymic'] ?? '') ?>">
                <select name="gender">
                    <option value="М" <?= (($current['gender'] ?? 'М') === 'М') ? 'selected' : '' ?>>М</option>
                    <option value="Ж" <?= (($current['gender'] ?? '')  === 'Ж') ? 'selected' : '' ?>>Ж</option>
                </select>
                <input type="date"  name="birthdate" value="<?= (!empty($current['birthdate']) && $current['birthdate'] !== '0000-00-00') ? htmlspecialchars($current['birthdate']) : '' ?>">
                <input type="text"  name="phone"    placeholder="Телефон" value="<?= htmlspecialchars($current['phone']   ?? '') ?>">
                <input type="text"  name="address"  placeholder="Адрес"   value="<?= htmlspecialchars($current['address'] ?? '') ?>">
                <input type="email" name="email"    placeholder="E-mail"  value="<?= htmlspecialchars($current['email']   ?? '') ?>">
                <textarea name="comment" placeholder="Комментарий"><?= htmlspecialchars($current['comment'] ?? '') ?></textarea>
                <button type="submit">Изменить запись</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php mysqli_close($mysqli); ?>