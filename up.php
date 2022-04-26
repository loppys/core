<?php

Loader::addModule(
  '_startup',
  Loader::TYPE_SYSTEM,
  \Vengine\Startup::class
);

Loader::addModule(
  'DataPageTransformer',
  Loader::TYPE_SYSTEM,
  \Vengine\Controllers\Page\DataPageTransformer::class
);

Loader::addModule(
  'LocalPage',
  Loader::TYPE_SYSTEM,
  \Vengine\Controllers\Page\LocalPage::class,
  [Loader::callModule('DataPageTransformer')]
);
