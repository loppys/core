<?php

class RenderPage extends Process
{
  protected $pageArr;
  protected $var;

  function __construct($page)
  {
    $this->pageArr = controller('page', $page);
    $this->var = $this->getVars();

    $this->render($page);
  }

  /**
   *  рендер страниц
   * @str name
   * @str page
   * @str file
   * @str class
   * @str module
   * @str url
   * @str path
   * @str custom_url
   * @arr tpl
   * @arr js
   * @arr param_cls
   * @arr module_cst
   * @str design
   */

   // Проброс данных из выполняемых классов --> составление head (html) --
   // -> подключение шаблонов --> подключение js (и/или до подключения шаблона) --
   // -> Должен только выводить и содержать минимум логики (должны приходить максимально чистые данные)
  public function render($page)
  {
    $pageArr = $this->pageArr;
    $var = $this->var;

    returnMethod('User', 'standart', 'checkReferalLink');

    if ($this->cache) {
      if (file_exists($this->tmpfile . 'cache-' . md5(date('Y-m-d')) . '.php')) {
        include $this->tmpfile . 'cache-' . md5(date('Y-m-d')) . '.php';
        return;
      }
    }

    if ($page == 'logout') {
      $this->logout($page);
    }

    if (empty($pageArr)) {
      $this->error404();
    }

    if ($pageArr->name == '') {
      $this->namePage = $page;
    }else{
      $this->namePage = $pageArr->name;
    }

    print '<!DOCTYPE html>
    <html>
    <head>
      <title>'. $this->namePage .'</title>
      <link rel="stylesheet" type="text/css" href="/../template/style.css">
      <link rel="stylesheet" type="text/css" href="/../template/_custom_style.css">
      <link rel="shortcut icon" href="images/favicon.png">
      <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body style="height: auto;">';

    require _File('head.tpl', 'core/template');

    $this->addScript([
      'src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"',
      'src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"',
      'src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"'
    ], true);

    print '<div class="main-container">
      <div class="container-content">';

    if ($pageArr->class) {
      returnMethod(
        $pageArr->class,
        $pageArr->param_cls,
        $pageArr->method,
        $pageArr->param,
        $pageArr->path,
        $pageArr->module
      );
    }

    if ($pageArr->module_cst) {
      $this->moduleCustom($pageArr->module_cst);
    }

    if ($pageArr->custom_url != '#%tpl_generate%#') {
      if (is_array($pageArr->tpl) && $pageArr->design == 'section') {
        foreach ($pageArr->tpl as $tpl) {
          print '<section>';
          $this->templateConnect($tpl);
          print '</section>';
        }
        }elseif ($pageArr->tpl) {
          $this->templateConnect($pageArr->tpl);
        }
    }

    if ($pageArr->js) {
      $this->addScript($pageArr->js);
    }

    print '</div>';

    require _File('footer.tpl', 'core/template');

    print '</body>
    </html>';
  }
}
