<?php
header('Content-Type: text/html; charset=utf-8');
session_start();

// Подключаем конфиг БД
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'friends');

// Валидация параметра p
$allowed_pages = ['viewer', 'add', 'edit', 'delete'];
if (!isset($_GET['p']) || !in_array($_GET['p'], $allowed_pages)) {
    $_GET['p'] = 'viewer';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Записная книжка</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f0f2f5;
            color: #333;
        }
        header {
            background: #1a237e;
            color: white;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        header h1 { font-size: 1.4rem; font-weight: 600; }
        #nav {
            background: #283593;
            padding: 0 24px;
        }
        #menu {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }
        #menu a {
            display: inline-block;
            padding: 12px 20px;
            color: #c5cae9;
            text-decoration: none;
            font-size: 0.95rem;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
        }
        #menu a:hover { color: white; background: rgba(255,255,255,0.1); }
        #menu a.selected {
            color: #ff5252;
            border-bottom-color: #ff5252;
            font-weight: 600;
        }
        #submenu {
            background: #3949ab;
            padding: 6px 24px;
            display: flex;
            gap: 4px;
        }
        #submenu a {
            display: inline-block;
            padding: 6px 14px;
            color: #c5cae9;
            text-decoration: none;
            font-size: 0.85rem;
            border-radius: 4px;
            transition: all 0.2s;
        }
        #submenu a:hover { background: rgba(255,255,255,0.15); color: white; }
        #submenu a.selected { color: #ff5252; font-weight: 600; background: rgba(255,82,82,0.1); }
        #content {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 28px 32px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        table th {
            background: #e8eaf6;
            color: #3949ab;
            padding: 12px 14px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #c5cae9;
        }
        table td {
            padding: 10px 14px;
            border-bottom: 1px solid #f0f0f0;
        }
        table tr:hover td { background: #f5f5ff; }
        .pagination {
            display: flex;
            gap: 6px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .pagination a, .pagination span {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .pagination a {
            background: #e8eaf6;
            color: #3949ab;
            border: 2px solid transparent;
            transition: border-color 0.2s;
        }
        .pagination a:hover { border-color: #3949ab; }
        .pagination span {
            background: #3949ab;
            color: white;
        }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #ddd;
            border-radius: 7px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
            font-family: inherit;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3949ab;
            box-shadow: 0 0 0 3px rgba(57,73,171,0.1);
        }
        .form-group textarea { resize: vertical; min-height: 80px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .btn {
            padding: 10px 24px;
            border: none;
            border-radius: 7px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }
        .btn:active { transform: scale(0.98); }
        .btn-primary { background: #3949ab; color: white; }
        .btn-primary:hover { background: #283593; }
        .msg-ok {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1.5px solid #a5d6a7;
            border-radius: 7px;
            padding: 12px 18px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .msg-err {
            background: #ffebee;
            color: #c62828;
            border: 1.5px solid #ef9a9a;
            border-radius: 7px;
            padding: 12px 18px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        h2 {
            font-size: 1.2rem;
            color: #1a237e;
            margin-bottom: 22px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e8eaf6;
        }
        #edit_links {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 24px;
        }
        #edit_links a, #edit_links div.current-item {
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 0.88rem;
            text-decoration: none;
        }
        #edit_links a {
            background: #e8eaf6;
            color: #3949ab;
            transition: background 0.2s;
        }
        #edit_links a:hover { background: #c5cae9; }
        #edit_links div.current-item {
            background: #3949ab;
            color: white;
            font-weight: 600;
            outline: 2px solid #ff5252;
            outline-offset: 2px;
        }
        #delete_links { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
        #delete_links a {
            padding: 10px 16px;
            background: #ffebee;
            color: #c62828;
            text-decoration: none;
            border-radius: 7px;
            font-size: 0.92rem;
            border: 1.5px solid #ef9a9a;
            transition: background 0.2s;
            max-width: 400px;
        }
        #delete_links a:hover { background: #ffcdd2; }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9e9e9e;
        }
        .empty-state svg { margin: 0 auto 16px; opacity: 0.4; }
        .badge-m { background: #bbdefb; color: #1565c0; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem; }
        .badge-f { background: #fce4ec; color: #880e4f; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem; }
    </style>
</head>
<body>
<header>
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
    </svg>
    <h1>Записная книжка</h1>
</header>

<nav id="nav">
<?php require 'menu.php'; echo getMenu(); ?>
</nav>

<div id="content">
<div class="card">
<?php
$p = $_GET['p'];
if ($p === 'viewer') {
    require 'viewer.php';

    if (!isset($_GET['pg']) || (int)$_GET['pg'] < 0) $_GET['pg'] = 0;
    $allowed_sorts = ['byid', 'fam', 'birth'];
    if (!isset($_GET['sort']) || !in_array($_GET['sort'], $allowed_sorts)) {
        $_GET['sort'] = 'byid';
    }
    echo getFriendsList($_GET['sort'], (int)$_GET['pg']);
} else {
    if (file_exists($_GET['p'] . '.php')) {
        include $_GET['p'] . '.php';
    }
}
?>
</div>
</div>
</body>
</html>
