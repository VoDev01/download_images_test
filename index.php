<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Скрипт тз</title>
</head>

<body>
    <p>Скрипт считает указанные ссылки на изображения и скачает их в локальную папку</p>
    <form method="post" action="backend.php" enctype="multipart/form-data">
        <div style="margin-bottom: 10px;">
            <label for="images">Ссылки на картинки</label>
            <input type="file" id="images" name="images" required />
        </div>
        <div style="margin-bottom: 10px;">
            <label for="saveDir">Путь сохранения</label>
            <input type="text" id="saveDir" name="saveDir" value="images" required />
        </div>
        <div style="margin-bottom: 10px;">
            <label for="logLevel">Уровень логгирования</label>
            <select name="logLevel" id="logLevel">
                <option value="1" selected>Информационный</option>
                <option value="2">Подробный</option>
                <option value="3">Отладочный</option>
            </select>
        </div>
        <button type="submit">Отправить</button>
    </form>
</body>

</html>