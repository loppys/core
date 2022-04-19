<?php

Loader::addModule(
  '_startup',
  Loader::TYPE_SYSTEM,
  \Vengine\Startup::class
);

Loader::callModule('_startup')->init();
