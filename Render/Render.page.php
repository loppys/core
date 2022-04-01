<?php

namespace Vengine\Render;

use Vengine\Process;

class RenderPage extends Process
{
  protected $pageArr;
  protected $var;
  private $html = array();
  protected $classObject;
  private $tmpFile;

  public $namePage;

  private $process;

  function __construct(Process $processObject)
  {
    $this->process = $processObject;
    $this->tmpFile = $this->process->tmpfile . 'cache-' . $this->process->page . '-' . md5(date('Y-m-d-H')) . '.php';
    $this->pageArr = returnObject('FindPage')->getPage($this->process->page);

    requireRenderFile('navButton');

    $this->prepare();
  }

  public function prepare()
  {
    $pageArr = $this->pageArr;

    if ($this->process->cache) {
      if (file_exists($this->tmpFile)) {
        include $this->tmpFile;
        return;
      }
    }

    if (empty($pageArr)) {
      $this->error404();
    }

    if ($pageArr->name == '') {
      $this->namePage = $pageArr->page;
    }else{
      $this->namePage = $pageArr->name;
    }

    if ($this->pageArr->type === 'admin') {
      $this->classObject = returnObject($pageArr->class, $pageArr->param_cls);

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

    $this->classObject = returnObject($pageArr->class, $pageArr->param_cls);

    if ($pageArr->tpl_custom === 'class') {
      if (method_exists($this->classObject, 'render')) {
        $this->html($this->classObject->render());
      } else {
        $this->html('Не удалось сгенерировать шаблон');
      }
    }

    if ($pageArr->tpl) {
      if (is_array($pageArr->tpl)) {
        foreach ($pageArr->tpl as $tpl) {
          $this->html($this->templateConnect($tpl));
        }
      }else{
        $this->html($this->templateConnect($pageArr->tpl));
      }
    }

    $this->html('</div></div>');

    $this->addFooter();

    if ($pageArr->js) {
      $this->html(
        $this->addScript([$pageArr->js])
       );
    }

    $this->html('</body></html>');

    $this->render();
  }

  public function render()
  {
    if ($this->pageArr->method) {
      $this->classObject->{$this->pageArr->method}();
    }

    foreach ($this->html as $key => $value) {
      if (stripos($value, 'file::') !== false) {
        $replace = [
          'file::' => ''
        ];

        $value = strtr($value, $replace);

        $this->html[$key] = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $value . '.tpl.php');
      }
    }

    $result = implode('', $this->html);

    if ($this->process->cache && $file = fopen($this->tmpfile, 'w+')) {
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
    <link rel="stylesheet" type="text/css" href="/../template/style.css">
    <link rel="stylesheet" type="text/css" href="/../template/_custom_style.css">
    <link rel="shortcut icon" href="images/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta itemprop="inLanguage" content="ru-RU">
    </head>';
  }

  private function addHeader()
  {
    if ($this->pageArr->type === 'page') {
      $this->html($this->templateConnect('head', 'core'));
    }
  }

  private function addFooter()
  {
    if ($this->pageArr->type === 'page') {
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
