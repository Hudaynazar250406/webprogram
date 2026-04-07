<?php
function db_connect() {
    $host = getenv('DB_HOST') ?: 'mysql';
    $port = (int)(getenv('DB_PORT') ?: 3306);
    $db   = getenv('DB_NAME') ?: 'friends';
    $user = getenv('DB_USER') ?: 'friends_user';
    $pass = getenv('DB_PASS') ?: 'friends_pass';

    mysqli_report(MYSQLI_REPORT_OFF);
    $mysqli = mysqli_connect($host, $user, $pass, $db, $port);
    if (!$mysqli) {
        die('<div style="max-width:900px;margin:40px auto;padding:20px;background:#fff;border-radius:12px;font-family:Arial">Ошибка подключения к БД: ' . htmlspecialchars(mysqli_connect_error()) . '</div>');
    }
    mysqli_set_charset($mysqli, 'utf8mb4');
    return $mysqli;
}
