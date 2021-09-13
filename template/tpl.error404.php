<!DOCTYPE html>
<html>
<head>
  <title>404</title>
  <link rel="stylesheet" href="/libs/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/template/style.css">
  <link rel="shortcut icon" href="images/favicon.png">
</head>
<body style="height: auto;">
<?php include 'tpl.head.php'; ?>
  <div style="justify-content: center; text-align: center; align-items: center; margin-top: 15%; height: 50%; width: 100%; font-size: 40px;">

  	<?= $this->tr('Страница не найдена', 'Page Not Found.'); ?>

  	<br>

  	<?= $this->tr('ОШИБКА 404', 'ERROR 404'); ?>

  	<br>

  	<a href="/" style="text-decoration: none;">
  		<?= $this->tr('На главную', 'Home'); ?>
  	</a>
  	</div>
<?php include 'tpl.footer.php'; ?>
</body>
</html>
