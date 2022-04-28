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
					<h1>Активация</h1>
				</span>
				<hr>
				<div>
					<label>Название*</label>
					<input name="nameProject" type="text" placeholder="Name Project" value="<?= $_REQUEST['nameProject'] ?>">
				</div>
				<div>
					<label>Ключ активации*</label>
					<input type="text" maxlength="30" min name="key" placeholder="XXXXX-XXXXX-XXXXX-XXXXX-XXXXXX">
				</div>
				<div>
					<button type="submit">Отправить запрос</button>
				</div>
			</form>
		</div>
	</body>
</html>
