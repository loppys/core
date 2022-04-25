<?php

namespace Vengine\libs\Migrations;

use Vengine\Database\Adapter;
use Vengine\libs\Migrations\Collect;

class Query extends Adapter
{
  public $migration;

  function __construct(Collect $collect)
  {
    $this->migration = $collect;

    $this->process();
  }

  public function process(): void
  {
    $data = $this->migration->data;
    foreach ($data as $key => $value) {
      $type = substr(stristr($value['file'], '.'), 1);

      if ($type !== 'sql') {
        return;
      }

      try {
        $query = file_get_contents($value['path']);

        if (count($data) > 0) {
          Adapter::exec($query);
          unset($data[$key]);
        }

        $db = Adapter::dispense('migration');
        $db->file = $value['file'];
        $db->completed = 'Y';
        $db->query = $query;
        Adapter::store($db);

      } catch (\Exception $e) {

        $db = Adapter::dispense('migration');
        $db->file = $value['file'];
        $db->completed = 'N';
        $db->text = $e->getMessage();
        Adapter::store($db);

      }
    }
  }
}
