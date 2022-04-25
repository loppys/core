<?php

namespace Vengine\Render;

use Vengine\Base;
use Vengine\Controllers\Routing\PageController;

class RenderPage extends Base
{
  public $namePage;
  public $type;

  protected $pageArr;
  private $html = array();

  private $process;

  function __construct(PageController $page)
  {
    parent::__construct();

    $this->prepare($page->page);
  }

  public function prepare($page)
  {
    if ($this->interface->cache) {
      if (file_exists($this->tmpFile)) {
        include $this->tmpFile;
        return;
      }
    }

    $this->type = $page->type;

    if (empty($page)) {
      $this->error404();
    }

    if ($page->name == '') {
      $this->namePage = $page->page;
    }else{
      $this->namePage = $page->name;
    }

    if ($page->type === 'admin') {
      $this->classObject = returnObject($page->class, $page->param_cls);

      if (method_exists($this->classObject, 'render')) {
        $this->html($this->classObject->render());
      } else {
        $this->html('Не удалось сгенерировать шаблон');
      }

      return $this->render();
    }

    $this->addHead();

    $this->html($this->addStandartJS());

    $this->addHeader();

    $this->html('<body>');
    $this->html([
      '<div class="__main-container">',
      '<div class="container-content">'
    ]);

    $this->classObject = returnObject($page->class, $page->param_cls);

    if ($page->tpl_custom === 'class') {
      if (method_exists($this->classObject, 'render')) {
        $this->html($this->classObject->render());
      } else {
        $this->html('Не удалось сгенерировать шаблон');
      }
    }

    if ($page->tpl) {
      if (is_array($page->tpl)) {
        foreach ($page->tpl as $tpl) {
          $this->html($this->templateConnect($tpl, $page->type_tpl));
        }
      }else{
        $this->html($this->templateConnect($page->tpl, $page->type_tpl));
      }
    }

    $this->html('</div></div>');

    $this->addFooter();

    if ($page->js) {
      $this->html(
        $this->addScript([$page->js])
       );
    }

    $this->html('</body></html>');

    $this->render($page);
  }

  public function render($page)
  {
    if ($page->method) {
      $this->classObject->{$page->method}();
    }

    foreach ($this->html as $key => $value) {
      if (stripos($value, 'file::') !== false) {
        $replace = [
          'file::' => ''
        ];

        $value = strtr($value, $replace);

        ob_start();
        include $value . '.tpl.php';
        $this->html[$key] = ob_get_contents();
        ob_clean();
      }
    }

    $result = implode('', $this->html);

    if (
      $this->interface->cache
      && $file = fopen($this->interface->tmpfile . 'cache-' . $page->page . '-' . md5(date('Y-m-d-H')) . '.php', 'w+')
    ) {
      if (is_writable($this->tmpFile)) {
        fwrite($file, $result);
        fclose($file);
      }
    }

    print $result;
  }

  private function addHead()
  {
    $this->html[] = '
    <!DOCTYPE html>
    <html>
    <head>
    <title>'. $this->namePage .'</title>
    <link rel="stylesheet" type="text/css" href="/../www/_template/style.css">
    <link rel="stylesheet" type="text/css" href="/../www/_template/_custom_style.css">
    <link rel="shortcut icon" href="www/images/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta itemprop="inLanguage" content="ru-RU">
    </head>';
  }

  private function addHeader()
  {
    if ($this->type === 'page') {
      $this->html($this->templateConnect('head', 'core'));
    }
  }

  private function addFooter()
  {
    if ($this->type === 'page') {
      $this->html($this->templateConnect('footer', 'core'));
    }
  }

  private function html($html)
  {
    if (is_array($html)) {
      foreach ($html as $key => $value) {
        $this->html[] = $value;
      }
    }else{
      $this->html[] = $html;
    }
  }
}
