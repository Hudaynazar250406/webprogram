<?php
require_once __DIR__ . '/config.php';

$mysqli = db_connect();
$msg = '';
$msgClass = 'ok';

if (isset($_POST['action']) && $_POST['action'] === 'add_contact') {
    $surname = trim($_POST['surname'] ?? '');
    $firstname = trim($_POST['firstname'] ?? '');
    $patronymic = trim($_POST['patronymic'] ?? '');
    $gender = in_array($_POST['gender'] ?? '', ['М', 'Ж'], true) ? $_POST['gender'] : 'М';
    $birthdate = trim($_POST['birthdate'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    if ($birthdate === '') {
        $birthdate = null;
    } elseif (preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $birthdate)) {
        $dt = DateTime::createFromFormat('d.m.Y', $birthdate);
        $birthdate = $dt ? $dt->format('Y-m-d') : null;
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthdate)) {
        $birthdate = null;
    }

    if ($surname === '' || $firstname === '') {
        $msg = 'Ошибка: заполните как минимум фамилию и имя.';
        $msgClass = 'error';
    } else {
        $stmt = $mysqli->prepare('INSERT INTO contacts (surname, firstname, patronymic, gender, birthdate, phone, address, email, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');

        if (!$stmt) {
            $msg = 'Ошибка подготовки запроса: ' . $mysqli->error;
            $msgClass = 'error';
        } else {
            $stmt->bind_param('sssssssss', $surname, $firstname, $patronymic, $gender, $birthdate, $phone, $address, $email, $comment);

            if ($stmt->execute()) {
                $msg = 'Запись добавлена.';
                $_POST = [];
            } else {
                $msg = 'Ошибка: запись не добавлена. ' . $stmt->error;
                $msgClass = 'error';
            }

            $stmt->close();
        }
    }
}
?>

<div class="content-card form-card">
    <h2>Добавление записи</h2>

    <?php if ($msg): ?>
        <div class="<?= $msgClass ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="post" action="?p=add" class="contact-form">
        <input type="hidden" name="action" value="add_contact">

        <input type="text" name="surname" placeholder="Фамилия" value="<?= htmlspecialchars($_POST['surname'] ?? '') ?>" required>
        <input type="text" name="firstname" placeholder="Имя" value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>" required>
        <input type="text" name="patronymic" placeholder="Отчество" value="<?= htmlspecialchars($_POST['patronymic'] ?? '') ?>">

        <select name="gender">
            <option value="М" <?= (($_POST['gender'] ?? 'М') === 'М') ? 'selected' : '' ?>>М</option>
            <option value="Ж" <?= (($_POST['gender'] ?? '') === 'Ж') ? 'selected' : '' ?>>Ж</option>
        </select>

        <input type="date" name="birthdate" value="<?= htmlspecialchars($_POST['birthdate'] ?? '') ?>">
        <input type="phone" name="phone" placeholder="Телефон" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        <input type="text" name="address" placeholder="Адрес" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
        <input type="email" name="email" placeholder="E-mail" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        <textarea name="comment" placeholder="Комментарий"><?= htmlspecialchars($_POST['comment'] ?? '') ?></textarea>

        <button type="submit">Добавить запись</button>
    </form>
</div>

<?php mysqli_close($mysqli); ?>