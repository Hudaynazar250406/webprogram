<?php
session_start();

if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = array();
    $_SESSION['iteration'] = 0;
}
$_SESSION['iteration']++;

// ---- isnum: проверка является ли строка числом (целым или дробным, возможно отрицательным) ----
function isnum($x) {
    if ($x === null || $x === '') return false;
    $x = (string)$x;
    if (strlen($x) === 0) return false;
    $start = 0;
    if ($x[0] === '-') {
        if (strlen($x) === 1) return false;
        $start = 1;
    }
    $pointcount = false;
    for ($i = $start; $i < strlen($x); $i++) {
        $c = $x[$i];
        if ($c === '.') {
            if ($pointcount) return false;
            $pointcount = true;
        } elseif ($c < '0' || $c > '9') {
            return false;
        }
    }
    // запрещаем ведущие нули: "048" не число
    $abs = substr($x, $start);
    if (strlen($abs) > 1 && $abs[0] === '0' && $abs[1] !== '.') return false;
    return true;
}

// ---- calculate: вычисление выражения БЕЗ скобок ----
function calculate($val) {
    $val = trim($val);
    if ($val === '' || $val === null) return 'Пустое выражение';

    // если уже число — вернуть
    if (isnum($val)) return $val;

    // 1. Сложение: разбиваем по '+'
    // Ищем '+' не в начале (чтобы не задеть унарный плюс)
    $args = array();
    $depth = 0;
    $current = '';
    for ($i = 0; $i < strlen($val); $i++) {
        $c = $val[$i];
        if ($c === '(') $depth++;
        elseif ($c === ')') $depth--;
        if ($c === '+' && $depth === 0 && $i > 0) {
            $args[] = $current;
            $current = '';
        } else {
            $current .= $c;
        }
    }
    $args[] = $current;

    if (count($args) > 1) {
        $sum = 0;
        foreach ($args as $arg) {
            $arg = calculate(trim($arg));
            if (!isnum($arg)) return $arg;
            $sum += (float)$arg;
        }
        return formatNum($sum);
    }

    // 2. Вычитание: разбиваем по '-' (не считая унарный минус в начале)
    $args = array();
    $current = '';
    for ($i = 0; $i < strlen($val); $i++) {
        $c = $val[$i];
        if ($c === '-' && $i > 0 && $val[$i-1] !== '*' && $val[$i-1] !== '/' && $val[$i-1] !== ':') {
            $args[] = $current;
            $current = '';
        } else {
            $current .= $c;
        }
    }
    $args[] = $current;

    if (count($args) > 1) {
        $first = calculate(trim($args[0]));
        if (!isnum($first)) return $first;
        $result = (float)$first;
        for ($i = 1; $i < count($args); $i++) {
            $arg = calculate(trim($args[$i]));
            if (!isnum($arg)) return $arg;
            $result -= (float)$arg;
        }
        return formatNum($result);
    }

    // 3. Умножение: разбиваем по '*'
    $args = explode('*', $val);
    if (count($args) > 1) {
        $first = calculate(trim($args[0]));
        if (!isnum($first)) return $first;
        $result = (float)$first;
        for ($i = 1; $i < count($args); $i++) {
            $arg = calculate(trim($args[$i]));
            if (!isnum($arg)) return $arg;
            $result *= (float)$arg;
        }
        return formatNum($result);
    }

    // 4. Деление: разбиваем по '/' или ':'
    $divVal = str_replace(':', '/', $val);
    $args = explode('/', $divVal);
    if (count($args) > 1) {
        $first = calculate(trim($args[0]));
        if (!isnum($first)) return $first;
        $result = (float)$first;
        for ($i = 1; $i < count($args); $i++) {
            $arg = calculate(trim($args[$i]));
            if (!isnum($arg)) return $arg;
            if ((float)$arg == 0) return 'Деление на ноль';
            $result /= (float)$arg;
        }
        return formatNum($result);
    }

    return 'Неизвестное выражение: ' . $val;
}

// форматирование числа: убираем лишние нули
function formatNum($n) {
    if (floor($n) == $n && abs($n) < 1e15) {
        return (string)(int)$n;
    }
    // до 10 знаков после запятой, без хвостовых нулей
    return rtrim(rtrim(number_format($n, 10, '.', ''), '0'), '.');
}

// ---- SqValidator: проверка корректности скобок ----
function SqValidator($val) {
    $open = 0;
    for ($i = 0; $i < strlen($val); $i++) {
        if ($val[$i] === '(') $open++;
        elseif ($val[$i] === ')') $open--;
        if ($open < 0) return false;
    }
    return $open === 0;
}

// ---- calculateSq: вычисление выражения СО скобками ----
function calculateSq($val) {
    $val = trim($val);
    if (!SqValidator($val)) return 'Неверная расстановка скобок';

    $start = strpos($val, '(');
    if ($start === false) return calculate($val);

    $end = $start + 1;
    $open = 1;
    while ($open > 0 && $end < strlen($val)) {
        if ($val[$end] === '(') $open++;
        elseif ($val[$end] === ')') $open--;
        $end++;
    }

    $inner = substr($val, $start + 1, $end - $start - 2);
    $innerResult = calculateSq($inner);
    if (!isnum($innerResult)) return $innerResult;

    $newval = substr($val, 0, $start) . $innerResult . substr($val, $end);
    return calculateSq($newval);
}

// ---- Обработка формы ----
$res = null;
$inputVal = '';
if (isset($_POST['val']) && isset($_POST['iteration']) &&
    ((int)$_POST['iteration'] + 1 === $_SESSION['iteration'])) {
    $inputVal = $_POST['val'];
    $res = calculateSq($inputVal);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Калькулятор — ЛР10</title>
<style>
  :root {
    --bg: #f4f3ef;
    --surface: #ffffff;
    --primary: #01696f;
    --primary-h: #0c4e54;
    --text: #1e1c18;
    --muted: #76746f;
    --border: #d4d0ca;
    --err-bg: #fce8f3;
    --err-text: #8b1a5e;
    --ok-bg: #e6f4e0;
    --ok-text: #2e5f10;
    --radius: 10px;
    --shadow: 0 4px 16px rgba(0,0,0,0.09);
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'Segoe UI', system-ui, sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2.5rem 1rem 3rem;
  }
  .wrap { width: 100%; max-width: 620px; }

  h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--primary);
    text-align: center;
    margin-bottom: 0.2rem;
  }
  .subtitle {
    text-align: center;
    color: var(--muted);
    font-size: 0.85rem;
    margin-bottom: 1.8rem;
  }

  .card {
    background: var(--surface);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 1.75rem;
    margin-bottom: 1.25rem;
  }

  label { font-weight: 600; font-size: 0.9rem; display: block; margin-bottom: 0.5rem; }

  .row { display: flex; gap: 0.6rem; }
  input[type=text] {
    flex: 1;
    padding: 0.7rem 1rem;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-size: 1.05rem;
    font-family: 'Courier New', monospace;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    background: #fafaf8;
  }
  input[type=text]:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(1,105,111,.13);
    background: #fff;
  }
  button[type=submit] {
    padding: 0.7rem 1.4rem;
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 0.97rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s;
    white-space: nowrap;
  }
  button[type=submit]:hover { background: var(--primary-h); }

  .hint { margin-top: 0.65rem; font-size: 0.8rem; color: var(--muted); line-height: 1.6; }
  .hint code { background: #eeecea; padding: 0.1rem 0.3rem; border-radius: 4px; }

  .result {
    margin-top: 1.1rem;
    padding: 0.85rem 1.1rem;
    border-radius: 8px;
    font-size: 1.05rem;
    font-family: 'Courier New', monospace;
    font-weight: 600;
  }
  .result.ok  { background: var(--ok-bg);  color: var(--ok-text);  border: 1px solid #b9dca8; }
  .result.err { background: var(--err-bg); color: var(--err-text); border: 1px solid #e0b0ce; }

  /* History */
  .hist-title {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 0.9rem;
  }
  .hist-list { list-style: none; display: flex; flex-direction: column; gap: 0.35rem; max-height: 260px; overflow-y: auto; }
  .hist-list li {
    font-family: 'Courier New', monospace;
    font-size: 0.88rem;
    padding: 0.45rem 0.8rem;
    background: #f6f5f1;
    border-radius: 6px;
  }
  .hist-list li.err { color: var(--err-text); background: #fce8f3; }
  .empty { text-align: center; color: var(--muted); font-size: 0.88rem; padding: 0.8rem 0; }

  .clear-btn {
    margin-top: 1rem;
    padding: 0.4rem 0.9rem;
    background: transparent;
    border: 1.5px solid var(--border);
    border-radius: 7px;
    font-size: 0.82rem;
    color: var(--muted);
    cursor: pointer;
    transition: all .2s;
  }
  .clear-btn:hover { border-color: var(--err-text); color: var(--err-text); }
</style>
</head>
<body>
<div class="wrap">
  <h1>Калькулятор</h1>
  <p class="subtitle">Лабораторная работа №10 — PHP · Сессии · Преобразование типов</p>

  <div class="card">
    <form method="POST" action="">
      <input type="hidden" name="iteration" value="<?= $_SESSION['iteration'] ?>">
      <label for="val">Введите арифметическое выражение</label>
      <div class="row">
        <input type="text" id="val" name="val"
               placeholder="Пример: (7435+48564756784)/2"
               value="<?= htmlspecialchars($inputVal) ?>"
               autocomplete="off">
        <button type="submit">Вычислить</button>
      </div>
      <div class="hint">
        Поддерживается: <code>+</code> <code>-</code> <code>*</code> <code>/</code> <code>:</code> скобки, целые и дробные числа.<br>
        Примеры: <code>2+3*4</code> → 14 &nbsp;|&nbsp; <code>(7435+100)/5</code> → 1507 &nbsp;|&nbsp; <code>10:4</code> → 2.5
      </div>
    </form>

    <?php if ($res !== null): ?>
      <?php $isErr = !isnum($res); ?>
      <div class="result <?= $isErr ? 'err' : 'ok' ?>">
        <?php if ($isErr): ?>
          ❌ Ошибка: <?= htmlspecialchars($res) ?>
        <?php else: ?>
          ✔ <?= htmlspecialchars($inputVal) ?> = <strong><?= htmlspecialchars($res) ?></strong>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="card">
    <div class="hist-title">📋 История вычислений</div>
    <?php if (empty($_SESSION['history'])): ?>
      <div class="empty">История пуста — введите первое выражение.</div>
    <?php else: ?>
      <ul class="hist-list">
        <?php foreach (array_reverse($_SESSION['history']) as $item): ?>
          <?php $e = strpos($item, '= ') !== false && !isnum(trim(substr($item, strpos($item,'= ')+2))); ?>
          <li class="<?= $e ? 'err' : '' ?>"><?= htmlspecialchars($item) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <?php
    // Сохраняем текущий результат в историю ПОСЛЕ вывода (попадёт при следующей загрузке)
    if ($res !== null) {
        $_SESSION['history'][] = $inputVal . ' = ' . $res;
    }
    ?>

    <?php if (!empty($_SESSION['history'])): ?>
      <form method="POST" action="clear.php">
        <button type="submit" class="clear-btn">🗑 Очистить историю</button>
      </form>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
