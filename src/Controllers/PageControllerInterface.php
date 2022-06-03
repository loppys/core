<?php

namespace Vengine\Controllers;

interface PageControllerInterface
{
  public function add(array $page): Object;

  public function delete(string $name): void;

  public function getList(): array;
}
