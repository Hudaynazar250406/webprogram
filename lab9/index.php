<?php
require_once __DIR__ . '/menu.php';
$p = $_GET['p'] ?? 'viewer';
$allowed = ['viewer', 'add', 'edit', 'delete'];
if (!in_array($p, $allowed, true)) {
    $p = 'viewer';
}
?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Записная книжка</title>
    <style>
        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#eef1f7;color:#1b1b1b}
        .topbar{background:#202a86;color:#fff;padding:20px 24px;font-size:20px;font-weight:700}
        .wrap{max-width:1200px;margin:0 auto;padding:0 16px 40px}
        .menu,.submenu{display:flex;gap:12px;flex-wrap:wrap}
        .menu{background:#2f3ea8;padding:14px 16px}
        .submenu{background:#3e4db7;padding:10px 16px;margin-bottom:24px}
        .menu-link,.submenu-link{color:#fff;text-decoration:none;padding:10px 14px;border-radius:8px;background:transparent}
        .menu-link.active,.submenu-link.active{background:#e74c3c;color:#fff}
        .content-card{background:#fff;border-radius:16px;padding:24px;box-shadow:0 8px 24px rgba(0,0,0,.08);overflow:auto}
        .contacts-table{width:100%;border-collapse:collapse;font-size:14px}
        .contacts-table th,.contacts-table td{border:1px solid #d9def0;padding:10px;vertical-align:top;text-align:left}
        .contacts-table th{background:#f2f5ff}
        .pagination{display:flex;gap:8px;flex-wrap:wrap;margin-top:20px}
        .page{display:inline-block;padding:8px 12px;border:1px solid #cdd4ec;border-radius:8px;text-decoration:none;color:#202a86;background:#fff}
        .page:hover{border:2px solid #202a86;padding:7px 11px}
        .page.current{background:#e74c3c;color:#fff;border-color:#e74c3c}
        .contact-form{display:grid;grid-template-columns:1fr;gap:12px;max-width:700px}
        .contact-form input,.contact-form select,.contact-form textarea{padding:12px;border:1px solid #cfd6ea;border-radius:10px;font-size:15px}
        .contact-form textarea{min-height:110px;resize:vertical}
        .contact-form button{background:#202a86;color:#fff;border:none;border-radius:10px;padding:12px 16px;font-size:15px;cursor:pointer}
        .contact-form button:hover{background:#16206d}
        .ok,.error,.empty{padding:12px 14px;border-radius:10px;margin-bottom:16px}
        .ok{background:#e8f8ee;color:#1f7a3f}
        .error{background:#fdeaea;color:#b42318}
        .empty{background:#f6f7fb;color:#51607a}
        .edit-links,.delete-links{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px}
        .edit-links a,.delete-link,.selected-link{padding:8px 12px;border-radius:8px;text-decoration:none;border:1px solid #cfd6ea}
        .edit-links a,.delete-link{color:#202a86;background:#fff}
        .selected-link{background:#e74c3c;color:#fff;border-color:#e74c3c}
        h2{margin-top:0}
        @media (max-width: 768px){.contacts-table{font-size:12px}.content-card{padding:16px}}
    </style>
</head>
<body>
    <div class="topbar">Записная книжка</div>
    <div class="wrap">
        <?php echo renderMenu(); ?>
        <?php
            if ($p === 'viewer') {
                require_once __DIR__ . '/viewer.php';
                $sort = $_GET['sort'] ?? 'byid';
                $pg = isset($_GET['pg']) ? (int)$_GET['pg'] : 0;
                echo getFriendsList($sort, $pg);
            } elseif ($p === 'add') {
                require_once __DIR__ . '/add.php';
            } elseif ($p === 'edit') {
                require_once __DIR__ . '/edit.php';
            } elseif ($p === 'delete') {
                require_once __DIR__ . '/delete.php';
            }
        ?>
    </div>
</body>
</html>
