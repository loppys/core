<?php
include $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';



if ($check->access < 4) {
  $this->error404();
}
if ($_GET['adm'] == '' || $_GET['access'] == '' || $_GET['perm'] == '') { #Access check
  $_GET['adm'] = $_SESSION[$this->session_name]->login;
  $_GET['access'] = $check->access;
  if ($check->access == 4) {
    $_GET['perm'] = 'admin';
  }elseif ($check->access == 5) {
    $_GET['perm'] = 'owner';
  }
}else {
  $this->error404();
}

// admin class
$admin = new Admin($dbtables);
$post = $_POST;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="template/style.css">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin Panel</title>
  <style type="text/css">
    form {
      max-width: 100vw;
      max-height: 500px;
      overflow: auto;
    }
  </style>
</head>
<body>

<?php include 'admin.tpl.php'; ?>

</body>
</html>
