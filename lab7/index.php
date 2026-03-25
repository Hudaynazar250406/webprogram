<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа А-7. Сортировка массивов</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10px;
        }
        .element_row td {
            padding: 4px 8px;
        }
        .index_cell {
            width: 30px;
            text-align: right;
            font-weight: bold;
            color: #555;
        }
        input[type="text"] {
            padding: 6px 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
        }
        select {
            padding: 6px 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 300px;
            margin-bottom: 10px;
        }
        input[type="button"], input[type="submit"] {
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            margin: 5px 5px 5px 0;
        }
        input[type="button"] {
            background-color: #4CAF50;
            color: white;
        }
        input[type="button"]:hover {
            background-color: #45a049;
        }
        input[type="submit"] {
            background-color: #2196F3;
            color: white;
        }
        input[type="submit"]:hover {
            background-color: #1976D2;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
    </style>
    <script>
        function setHTML(element, txt)
        {
            if(element.innerHTML !== undefined)
                element.innerHTML = txt;
            else
            {
                var range = document.createRange();
                range.selectNodeContents(element);
                range.deleteContents();
                var fragment = range.createContextualFragment(txt);
                element.appendChild(fragment);
            }
        }

        function addElement()
        {
            var t = document.getElementById('elements');
            var index = t.rows.length;
            var row = t.insertRow(index);

            // Ячейка с номером элемента
            var celIndex = row.insertCell(0);
            celIndex.className = 'index_cell';
            setHTML(celIndex, index + ':');

            // Ячейка с полем ввода
            var cel = row.insertCell(1);
            cel.className = 'element_row';
            var celcontent = '<input type="text" name="element' + index + '">';
            setHTML(cel, celcontent);

            // Обновляем количество полей в скрытом поле
            document.getElementById('arrLength').value = t.rows.length;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Сортировка массивов</h1>
        <p>Введите элементы массива и выберите алгоритм сортировки.</p>

        <form method="POST" action="sort.php" target="_blank">

            <label>Элементы массива:</label>
            <table id="elements">
                <tr>
                    <td class="index_cell">0:</td>
                    <td class="element_row"><input type="text" name="element0"></td>
                </tr>
            </table>

            <input type="hidden" name="arrLength" id="arrLength" value="1">

            <input type="button" value="Добавить еще один элемент" onClick="addElement();">

            <br><br>

            <label>Алгоритм сортировки:</label>
            <select name="algoritm">
                <option value="0">Сортировка выбором</option>
                <option value="1">Пузырьковый алгоритм</option>
                <option value="2">Алгоритм Шелла</option>
                <option value="3">Алгоритм садового гнома</option>
                <option value="4">Быстрая сортировка</option>
                <option value="5">Встроенная функция PHP (sort)</option>
            </select>

            <br><br>

            <input type="submit" value="Сортировать массив">

        </form>
    </div>
</body>
</html>
