<?php

/**
 * Данные для авторизации в api
 */
  $k = 'nir)%_(jicbi0kburc60lpchar9hf4g3cg4s8'; //key
  $p = 'G^()%_(j64h6584JJkflld095204'; //password
  $k_two = 'test'; //Временно

 /**
  * Если без понятия что делать, то оставляем как есть!
  * ---
  * version - версия api
  * ---
  * format
  * all - использовать все доступные форматы
  * onlyMethod - использует указанный метод (не зависит от расположения)
  * core - обращаться только к ядру, исключая все модули
  * modules - обращаться только к модулям, исключая все остальное
  * ---
  * nProtected - подключение без авторизации (можно использовать, например, для новостей)
  * standart - подключение с авторизацией
  * sProtected - подключение с авторизацией и указанием индивидуального ключа
  */
  $settigns = [
    'version' => 'v1',
    'format' => 'all',
    'secure' => array(
      'method' => 'standart',
      'key_two' => $k_two
    )
  ];

  $autchData = [
    'password' => $p,
    'key' => $k
  ];
