<?php
$msg = '';

if (isset($_POST['action']) && $_POST['action'] === 'add_contact') {
    $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (mysqli_connect_errno()) {
        $msg = '<div class="msg-err">Ошибка подключения к БД: ' . mysqli_connect_error() . '</div>';
    } else {
        mysqli_set_charset($mysqli, 'utf8mb4');

        $surname    = htmlspecialchars(trim($_POST['surname'] ?? ''));
        $firstname  = htmlspecialchars(trim($_POST['firstname'] ?? ''));
        $patronymic = htmlspecialchars(trim($_POST['patronymic'] ?? ''));
        $gender     = in_array($_POST['gender'] ?? '', ['М', 'Ж']) ? $_POST['gender'] : 'М';
        $birthdate  = $_POST['birthdate'] ?? '';
        $phone      = htmlspecialchars(trim($_POST['phone'] ?? ''));
        $address    = htmlspecialchars(trim($_POST['address'] ?? ''));
        $email      = htmlspecialchars(trim($_POST['email'] ?? ''));
        $comment    = htmlspecialchars(trim($_POST['comment'] ?? ''));

        if (empty($surname) || empty($firstname)) {
            $msg = '<div class="msg-err">Ошибка: Фамилия и Имя обязательны для заполнения.</div>';
        } else {
            $stmt = $mysqli->prepare("INSERT INTO contacts (surname, firstname, patronymic, gender, birthdate, phone, address, email, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssssss', $surname, $firstname, $patronymic, $gender, $birthdate, $phone, $address, $email, $comment);

            if ($stmt->execute()) {
                $msg = '<div class="msg-ok">✓ Запись успешно добавлена!</div>';
            } else {
                $msg = '<div class="msg-err">Ошибка: запись не добавлена. ' . $mysqli->error . '</div>';
            }
            $stmt->close();
        }
        mysqli_close($mysqli);
    }
}
?>
<h2>Добавление контакта</h2>
<?= $msg ?>
<form method="post" action="/?p=add">
    <input type="hidden" name="action" value="add_contact">
    <div class="form-row-3">
        <div class="form-group">
            <label>Фамилия *</label>
            <input type="text" name="surname" placeholder="Иванов" required>
        </div>
        <div class="form-group">
            <label>Имя *</label>
            <input type="text" name="firstname" placeholder="Иван" required>
        </div>
        <div class="form-group">
            <label>Отчество</label>
            <input type="text" name="patronymic" placeholder="Иванович">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Пол</label>
            <select name="gender">
                <option value="М">Мужской</option>
                <option value="Ж">Женский</option>
            </select>
        </div>
        <div class="form-group">
            <label>Дата рождения</label>
            <input type="date" name="birthdate">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Телефон</label>
            <input type="tel" name="phone" placeholder="+7 (900) 000-00-00">
        </div>
        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" placeholder="example@mail.ru">
        </div>
    </div>
    <div class="form-group">
        <label>Адрес</label>
        <input type="text" name="address" placeholder="г. Москва, ул. Примерная, д. 1">
    </div>
    <div class="form-group">
        <label>Комментарий</label>
        <textarea name="comment" placeholder="Дополнительная информация..."></textarea>
    </div>
    <button type="submit" class="btn btn-primary">+ Добавить запись</button>
</form>
