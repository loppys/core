<?php

use Vengine\Packages\Modules\ModuleInfo;

/**
 * @var $moduleList array<ModuleInfo>
 * @var $libs array<array<string>>
 */

$currentTime = time();
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>vEngine | Debug Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

<br>
<h4>
    Информация о сервере:
</h4>

<table class="table">
    <thead>
    <tr>
        <th scope="col"></th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>Версия PHP</td>
            <td><?php print PHP_VERSION; ?></td>
        </tr>
        <tr>
            <td>ID версии PHP</td>
            <td><?php print PHP_VERSION_ID ?></td>
        </tr>
        <tr>
            <td>Корневая директория сайта</td>
            <td><?php print $_SERVER['DOCUMENT_ROOT']; ?></td>
        </tr>
        <tr>
            <td>Исполняемый скрипт</td>
            <td><?php print $_SERVER['SCRIPT_FILENAME']; ?></td>
        </tr>
        <tr>
            <td>IP сервера</td>
            <td><?php print $_SERVER['SERVER_ADDR']; ?></td>
        </tr>
        <tr>
            <td>Текущее время сервера (конвертированное)</td>
            <td><?php print date('d.m.Y H:i:sP', $currentTime); ?></td>
        </tr>
        <tr>
            <td>Текущее время сервера (unix)</td>
            <td><?php print $currentTime; ?></td>
        </tr>
        <tr>
            <td>Часовой пояс</td>
            <td><?php print date_default_timezone_get(); ?></td>
        </tr>
        <tr>
            <td>Engine</td>
            <td><a href="<?php print $_SERVER['engine.site']; ?>"><?php print $_SERVER['engine.name']; ?></a></td>
        </tr>
        <tr><td></td><td></td></tr>
        <tr>
            <th scope="row">Database</th>
            <td></td>
        </tr>
        <tr>
            <td>Type</td>
            <td><?php print $dbType ?></td>
        </tr>
        <tr>
            <td>Host</td>
            <td><?php print $dbHost ?></td>
        </tr>
        <tr>
            <td>DB Name</td>
            <td><?php print $dbName ?></td>
        </tr>
        <tr>
            <td>Login</td>
            <td><?php print $dbLogin ?></td>
        </tr>
        <tr>
            <td>Password</td>
            <td>*****</td>
        </tr>
        <tr><td></td><td></td></tr>
        <tr>
            <th scope="row">App config</th>
            <td></td>
        </tr>
        <tr>
            <td>Project name</td>
            <td><?php print $config['app']['project'] ?></td>
        </tr>
        <tr>
            <td>UUID</td>
            <td><?php print $config['app']['uuid'] ?></td>
        </tr>
        <tr>
            <td>Key</td>
            <td><?php print $config['app']['key'] ?></td>
        </tr>
        <tr>
            <td>Service Token</td>
            <td><?php print $config['services']['token'] ?></td>
        </tr>
        <tr><td></td><td></td></tr>
        <tr>
            <td>Доступ к сайту</td>
            <td>
            	<button>Закрыть</button>
            	<button>Открыть</button>
            </td>
        </tr>
        <tr>
            <td>Текущий доступ</td>
            <td>
            	Блокировка не применена
            </td>
        </tr>
        <tr>
            <td>Api token</td>
            <td>
                <?php print $this->config->token; ?>
            </td>
        </tr>
        <tr>
            <td>Потребление ОЗУ</td>
            <td>
                <?php print memory_get_peak_usage(true) / 1000000 . " MB"; ?>
            </td>
        </tr>
        <tr>
            <td>Свободное место на диске</td>
            <td>
                <?php print disk_free_space($_SERVER['DOCUMENT_ROOT']) / 1000000 . " MB" ?>
            </td>
        </tr>
    </tbody>
</table>

<br>
<h4>
    Информация о модулях vEngine (В таблице учитываются и пользовательские модули, которые были подключены корректно):
    <h6> # - это порядок запуска </h6>
</h4>
<hr>

<table class="table">
    <thead>
    <tr>
    	<th scope="col">#</th>
        <th scope="col">Название</th>
        <th scope="col">Версия</th>
        <th scope="col">Разработчик</th>
        <th scope="col">Описание</th>
        <th scope="col">Состояние</th>
        <th scope="col">Системный</th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 0; ?>
    <?php foreach ($moduleList as $key => $value): ?>
    <tr>
    	<td><?php print ++$i; ?></td>
        <th scope="row"><?php print $value->getName(); ?></th>
        <td><?php print $value->getVersion(); ?></td>
        <td><?php print $value->getDeveloper(); ?></td>
        <td><?php print $value->getDescription(); ?></td>
        <td><?php print $value->isLoaded() ? 'Создан' : 'Не создан'; ?></td>
        <td><?php print $value->isSystem() ? 'Да' : 'Нет'; ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<br>
<br>
<h4>Все установленные библиотеки (через composer):</h4>
<hr>

<table class="table">
    <thead>
    <tr>
        <th scope="col">Название</th>
        <th scope="col">Версия</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($libs as $lib): ?>
        <tr>
            <td><?php print $lib['name']; ?></td>
            <td><?php print $lib['version']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
