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

  function __construct(PageController $data)
  {
    $this->prepare($data);
  }

  public function prepare($data)
  {
    $this->interface = $this->getInterface();

    $page = $data->page;
    $parameters = $data->parameters;

    if ($this->interface->cache) {
      //К 2.0 реализовать
    }

    $this->type = $page->type;

    if (empty($page)) {
      $this->error404();
    }

    if ($page->name == '') {
      $this->namePage = $page->url;
    }else{
      $this->namePage = $page->name;
    }

    if ($page->render === 'admin') {
      $this->classObject = \Loader::callModule('CMS');

      if (method_exists($this->classObject, 'render')) {
        $this->html($this->classObject->render());
      } else {
        $this->html('Не удалось сгенерировать шаблон');
      }

      return $this->render($page);
    }

    $this->addHead();

    $this->html($this->addStandartJS());

    $this->addHeader();

    $this->html('<body>');
    $this->html([
      '<div class="__main-container">',
      '<div class="container-content">'
    ]);

    if (!$controller = $parameters['controller']) {
      $controller = $data->controller;
    }

    $this->classObject = \Loader::callModule($controller);

    if ($page->render !== 'standart') {
      if (method_exists($this->classObject, $page->render)) {
        $this->html($this->classObject->{$page->render}());
      } else {
        $this->html('Не удалось сгенерировать шаблон');
      }
    }

    if ($page->tpl) {
      if (is_array($page->tpl)) {
        foreach ($page->tpl as $tpl) {
          $this->html($this->templateConnect($tpl, $page->path));
        }
      }else{
        $this->html($this->templateConnect($page->tpl, $page->path));
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

    print $result;
  }

  private function addHead()
  {
    $this->html[] = '
    <!DOCTYPE html>
    <html>
    <head>
    <title>'. $this->namePage .'</title>
    <link rel="stylesheet" type="text/css" href="/tpl/style.css">
    <link rel="stylesheet" type="text/css" href="/tpl/_custom_style.css">
    <link rel="shortcut icon" href="/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta itemprop="inLanguage" content="ru-RU">
    </head>';
  }

  private function addHeader()
  {
    if ($this->type === 'page') {
      $this->html($this->templateConnect('head', 'CORE'));
    }
  }

  private function addFooter()
  {
    if ($this->type === 'page') {
      $this->html($this->templateConnect('footer', 'CORE'));
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
