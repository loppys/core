<?php

/**
 * CMS
 */
class CMS extends Process
{
  public function initCMS()
  {
    $post = $_POST;
    $get = $_GET;
    $page = $this->page;
    $chapter = substr($page, strrpos($page, '/') + 1, 100);
    $setting = $this->returnSettigns();
    $admin = new Admin($setting['settings']['database']['dbtables']);
    include 'template/tpl.php';
  }
}

/**
 * Админка)
 */
class Admin
{
  public $url = '/admin/';
  private $dbtables;

  function __construct($dbtables) {
  $this->dbtables = $dbtables;
  }

  public function admteam()
  {
  include $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';
  $load = R::findAll($this->dbtables);
  foreach ($load as $show) {
    if ($show->access >= 4) {
      echo '|ID-' . $show->id . '| |LOGIN-' . $show->login . '| |LEVEL_ACCESS-' . $show->access . '| |BALANCE-' . $show->balance . '<br><br>';
      }
    }
  }

  public function supteam()
  {
  include $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';
  $load = R::findAll($this->dbtables);
  foreach ($load as $show) {
    if ($show->access >= 2 && $show->access < 4) {
      echo '|ID-' . $show->id . '| |LOGIN-' . $show->login . '| |LEVEL_ACCESS-' . $show->access . '| |BALANCE-' . $show->balance . '<br><br>';
    }
    }
  }

  public function user($id)
  {
  include $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';
  $load = R::load($this->dbtables, $id);
  return $load . '<br>';
  }

  public function usercount()
  {
  include $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';
  $count = R::count($this->dbtables);
  return $count .'<br>';
  }

  public function productlist($button)
  {
  include $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';
  $load = R::findAll($settings['database']['dbproduct']);
  if (isset($button)) {
    foreach ($load as $product) {
      echo $product . '<br><br>';
      }
    }
  }

  public function productcount()
  {
  include $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';
  $count = R::count($settings['database']['dbproduct']);
  return $count;
  }

  public function userlist($button)
  {
  include $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';
  $load = R::findAll($this->dbtables);
  if (isset($button)) {
    foreach ($load as $users) {
      echo $users . '<br>';
      }
    }
  }

  public function addproduct($name, $description, $price, $type, $market, $button)
  {
  include $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';
  if (isset($button) && $name != '' && $description != '' && $price != '' && $type != '' && $market != '') {
    $load = R::dispense($settings['database']['dbproduct']);
    if ($load) {
      $load->name = $name;
      $load->description = $description;
      $load->price = $price;
      $load->type = $type;
      $load->release = date("Y-m-d H:i:s");
      $load->market = $market;
      R::store($load);
      print 'Last add- ' . '{' . $name . ' | ' . $description . ' | ' . $price . ' | ' . $type . ' | ' . $market . '} <br>';
      }
    }
  }

  // public function setaccess($id, $level)
  // {
  //   include $_SERVER['DOCUMENT_ROOT'] . '/settings.php';

  //   if (condition) {
  //     # code...
  //   }
  // }

  public function setsub($id, $value, $date)
  {

  }

  public function setbalance($id, $values)
  {
  include $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';
  $load = R::load($this->dbtables, $id);
  if ($values != '') {
      $load->balance += $values;
      R::store($load);
    }
  }

  public function unbalance($id, $values)
  {
    include $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';
    $load = R::load($this->dbtables, $id);
    if ($values != '') {
      $load->balance -= $values;
      R::store($load);
    }
  }

  public function read_log($button)
  {
    include $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';
    if (isset($button)) {
        exit("<meta http-equiv='refresh' content='0; url= /?_LOG_MODE=REDIR'>");
    }
  }

  public function news($button, $title, $intro, $autor, $text)
  {
    if (isset($button)) {
      $new = R::dispense('news');
      $new->title = $title;
      $new->intro = $intro;
      $new->autor = $autor;
      $new->text = $text;
      $new->date = date("Y-m-d H:i:s");
      R::store($new);
    }
  }

  public function adminCheck($check)
  {
    include $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';
    $user = $_SESSION[$this->session_name];
    if ($check->access >= 4) { #Access level check
      echo '<form method="POST"><input type="submit" name="admin_panel" value="Admin panel"></form><br>';
    }
  }

  function __destruct() {}

}
