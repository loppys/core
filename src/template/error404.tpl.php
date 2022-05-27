<!DOCTYPE html>
<html>
<head>
  <title>404</title>
  <link rel="stylesheet" href="/systpl/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/tpl/style.css">
  <link rel="shortcut icon" href="/img/favicon.png">
</head>
<body style="height: auto;">
<?php include 'head.tpl.php'; ?>
  <div style="justify-content: center; text-align: center; align-items: center; margin-top: 15%; height: 50%; margin-bottom: 15%; width: 100%; font-size: 40px;">

  	<?= $this->tr('Страница не найдена', 'Page Not Found.'); ?>

  	<br>

  	<?= $this->tr('ОШИБКА 404', 'ERROR 404'); ?>

  	<br>

  	<a href="/" style="text-decoration: none;">
  		<?= $this->tr('На главную', 'Home'); ?>
  	</a>
  	</div>
<?php include 'footer.tpl.php'; ?>
</body>
</html>
