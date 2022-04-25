<!DOCTYPE html>
<html>
	<head>
		<title>Установка</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="vendor/vengine/core/Install/template/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
    <div class="container">
      <form method="post">
				<span style="text-align: center">
					<h1>Установка</h1>
					<h5>Версия ядра: <?= $version; ?></h5>
				</span>
				<hr>
        <span style="text-align: center">
          <h4>Общие настройки</h4>
        </span>
  			<div>
  				<label>Название*</label>
  				<input type="text" value="<?= $nameProject ?>" disabled>
  			</div>
  			<div>
  				<label>Ключ активации*</label>
  				<input type="text" placeholder="XXXXX-XXXXX-XXXXX-XXXXX-XXXXXX" value="<?= $key ?>" disabled>
  			</div>
  			<div>
  				<label>Папка проекта*</label>
  				<input type="text" value="ROOT:/" disabled> PROJECT
  			</div>
        <div>
          <label>www*</label>
          <input name="wwwFolder" type="text" value="PROJECT:www/"> WWW
        </div>
  			<div>
  				<label>Временная папка*</label>
  				<input name="tmpDir" type="text" value="PROJECT:_tmp/"> TEMP
  			</div>
  			<div>
  				<label>Папка конфига*</label>
  				<input name="configFolder" type="text" value="PROJECT:config/"> CONFIG
  			</div>
        <div>
          <label>Папка для логов*</label>
          <input name="logsFolder" type="text" value="WWW:logs/"> LOGS
        </div>
				<span style="text-align: center">
					<h5>Если не знаете, то лучше оставить стандартные значения</h5>
				</span>
        <div>
          <ul>
            <li>
              <label><input type="checkbox" name="newConfig" checked>Сформировать новый конфиг</label>
            </li>
            <li>
              <label><input type="checkbox" name="cache">Включить кэш (Параметр будет добавлен, но разработка не закончена)</label>
            </li>
            <li>
              <label><input type="checkbox" name="logs" checked>Логирование в файл</label>
            </li>
            <li>
              <label><input type="checkbox" name="htaccess">Сформировать .htaccess</label>
            </li>
          </ul>
        </div>
        <hr>
        <span style="text-align: center">
          <h4>Модули</h4>
        </span>
        <div>
          <ul>
            <li>
              <label><input type="checkbox" name="dbExists" checked>Стандартные модули</label>
            </li>
            <li>
              <label><input type="checkbox" name="dbExists">Установить модуль API (Можно установить вручную. {vengine-modules/api})</label>
            </li>
          </ul>
        </div>
        <hr>
        <span style="text-align: center">
          <h4>Параметры базы данных</h4>
        </span>
        <div>
          <label>Тип*</label>
          <input name="dbType" type="text" placeholder="mysql">
        </div>
        <div>
          <label>Хост*</label>
          <input name="dbHost" type="text" placeholder="localhost">
        </div>
        <div>
  				<label>Логин*</label>
  				<input name="dbLogin" type="text" placeholder="Логин">
  			</div>
        <div>
  				<label>Пароль*</label>
  				<input name="dbPassword" type="password" placeholder="Пароль">
  			</div>
        <div>
          <label>Название базы*</label>
          <input name="dbName" type="text" placeholder="Название">
        </div>
        <div>
  				<ul>
  					<li>
  						<label><input type="checkbox" name="dbExists">Базы данных уже существуют**</label>
  					</li>
  				</ul>
  			</div>
        <hr>
        <span style="text-align: center">
          <h4>Планировщик (необязательно)</h4>
        </span>
        <div>
          <label>Тип*</label>
          <input name="dbType" type="text" placeholder="mysql">
        </div>
        <div>
          <label>Хост*</label>
          <input name="dbHost" type="text" placeholder="localhost">
        </div>
        <div>
  				<label>Логин*</label>
  				<input name="dbLogin" type="text" placeholder="Логин">
  			</div>
        <div>
  				<label>Пароль*</label>
  				<input name="dbPassword" type="password" placeholder="Пароль">
  			</div>
        <div>
          <label>Название базы*</label>
          <input name="dbName" type="text" placeholder="Название">
        </div>
        <div>
          <ul>
            <li>
              <label><input type="checkbox" name="dbSheduler">Использовать основную базу</label>
            </li>
          </ul>
        </div>

				<hr>

				<div>
					<ul>
						<li>
							<label><input type="checkbox" name="autoUpdate">Авто-обновления (Работает только с планировщиком)</label>
						</li>
						<li>
							<label><input type="checkbox" name="developVer">Установить нестабильную версию (не стоит включать авто-обновления)</label>
						</li>
						<li>
							<label><input type="checkbox" name="developVer">Сервер баз данных позволяет подключаться с других серверов</label>
						</li>
					</ul>
				</div>

        <span style="text-align: center">
          <h4>*Обязательные поля</h4>
          <h4>**Если базы данных существуют, то будет пропущена стадия создания</h4>
        </span>
  			<div>
  				<label></label>
  				<button type="submit" name="submit" value="submit">Отправить запрос</button>
  				<button type="reset">Сбросить</button>
  			</div>
  		</form>
    </div>
	</body>
</html>
