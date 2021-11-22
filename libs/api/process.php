<?php

class Api_Process extends Process
{
  public $moduleName = 'api';

  protected const SECURE_API = 'undefined';

  /**
   * Конструктор класса
   */
  function __construct($arr)
  {
    if (file_exists('config.php')) {
      return errorCode('Настройки API отсутствуют', '001', 'api');
    }

    require 'config.php';

    if (!is_array($arr)) {
      return errorCode('Непредвиденная ошибка', '002', 'api');
    }

    if ($arr) {
      foreach ($arr as $key => $value) {
        $param[$key] = $value;
      }

      $this->setSucure($settigns['secure']['method']) ? $this->prepare($param) : errorCode('Метод авторизации не указан', '003', 'api');
    }
  }

  public function setSucure($secureMethod): bool
  {
    if (empty($secureMethod)) {
      return false;
    }

    switch ($secureMethod) {
      case 'standart':
      define('SECURE_API', 'standart');
        return true;
        break;
      case 'nProtected':
      define('SECURE_API', 'nProtected');
        return true;
        break;
      case 'sProtected':
      define('SECURE_API', 'sProtected');
        return true;
        break;

      default:
        return false;
        break;
    }
  }

  public function prepare($param)
  {
    switch (SECURE_API) {
      case 'standart':
        $tpl = [
          'v' => 'v',
          'token' => 'token',
          'func' => 'func',
          'param' => 'param',
          'password' => 'password',
          'key' => 'key'
        ];
        break;
      case 'nProtected':
        $tpl = [
          'v' => 'v',
          'token' => 'token',
          'func' => 'func',
          'param' => 'param'
        ];
        break;
      case 'sProtected':
        $tpl = [
          'v' => 'v',
          'token' => 'token',
          'func' => 'func',
          'param' => 'param',
          'password' => 'password',
          'key' => 'key',
          'key_two' => 'key_two'
        ];
        break;

      default:
        return false;
        break;
    }

    foreach ($param as $key => $value) {
      $key_param[$key] = $key;
    }

    if ($key_param !== $tpl) {
      return errorCode('Запрос не совпадает с внутренним стандартом', '004', 'api');
    }

    if ($param['v'] === '1') {
      $data['token'] = $param['token'];
      $data['func'] = $param['func'];
      $data['param'] = $param['param'];
      if (SECURE_API == 'standart') {
        $data['password'] = $param['password'];
        $data['key'] = $param['key'];

        if (!$this->validKey($data['key']) || !$this->validPassword($data['password'])) {
          return errorCode('Пароль и/или ключ не правильные', '005<1>', 'api');
        }
      }
      if (SECURE_API == 'sProtected') {
        $data['password'] = $param['password'];
        $data['key'] = $param['key'];
        $data['key_two'] = $param['key_two'];

        if (!$this->validKey($data['key'], $data['key_two']) || !$this->validPassword($data['password'])) {
          return errorCode('Пароль и/или ключ не правильные', '005<2>', 'api');
        }
      }

      $this->process($data);
    } else {
      return errorCode('Что-то пошло не так!', '006', 'api');
    }
  }

  private function validKey($key, $key_two = false): bool
  {
    require 'config.php';

    if ($key === $autchData['key']) {
      if ($key_two) {
        return $key_two !== $settigns['secure']['key_two'] ? false : true;
      }
      return true;
    }

    return false;
  }

  private function validPassword($password): bool
  {
    require 'config.php';

    if ($password === $autchData['password']) {
      return true;
    }

    return false;
  }

  public function process($data)
  {
    $url = $this->pageFix();
    $method = substr($this->page, 15, 8);

    if (empty($method)) {
      return errorCode('Невозможно выполнить', '007', 'api');
    }

    switch ($method) {
      case 'get':
        return tr(13214);
          // return controller('api', $data);
        break;
      case 'post':
        // code...
        break;
      case 'delete':
        // code...
        break;

      default:
        return errorCode('метод для выполнения не указан', '008', 'api');
        break;
    }

    /**
     * Чтобы это заработало - нужна магия, иначе никак)
     */
    return errorCode('Что-то явно пошло не так :)', '009', 'api');
  }

}
