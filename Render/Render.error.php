<?php

/**
 * Подготовка и отображение сообщений ошибок
 */
class ErrorDisplay
{

  function __construct($errors)
  {
    $this->renderError($errors);
  }

  /*
  * Выводит сообщения об ошибках
  */
  public function renderError($errors)
  {
    if ($errors) {
      foreach ($errors as $value) {

          print '<div style="margin-top:10px" class="alert alert-danger" role="alert">
          <label>' . $value . '<br>
          </label>
          </div>';

      }
    }
  }
}
