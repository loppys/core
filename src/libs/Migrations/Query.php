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

        $this->migrationLog($value['file'], 'Y', $query);

      } catch (\Exception $e) {

        $this->migrationLog($value['file'], 'N', '', $e->getMessage());

      }
    }
  }

  private function migrationLog(string $file, string $completed, string $query, string $error = ''): void
  {
    $db = Adapter::dispense('migration');
    $db->file = $file;
    $db->completed = $completed;

    if ($query) {
      $db->query = $query;
    }

    if ($error) {
      $db->fail = $error;
    }

    Adapter::store($db);
  }
}
