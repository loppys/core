<?php

Loader::addModule(
  'DataPageTransformer',
  Loader::TYPE_SYSTEM,
  \Vengine\Controllers\Page\DataPageTransformer::class
);

Loader::addModules(
  [
    [
      '_startup',
      Loader::TYPE_SYSTEM,
      \Vengine\Startup::class
    ],
    [
      'LocalPage',
      Loader::TYPE_SYSTEM,
      \Vengine\Controllers\Page\LocalPage::class,
      [Loader::callModule('DataPageTransformer')]
    ]
  ]
);
