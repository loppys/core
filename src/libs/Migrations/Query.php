<?php

namespace Vengine\libs\Migrations;

use Vengine\System\Components\Database\Adapter;

/**
 * @deprecated
 */
class Query
{
    public function __construct(Collect $collect)
    {
        $this->process($collect);
    }

    public function process(Collect $collect): void
    {
        $data = $collect->data;

        if (empty($data)) {
            return;
        }

        foreach ($data as $key => $value) {
            $type = substr(stristr($value['file'], '.'), 1);

            if ($type !== 'sql') {
                continue;
            }

            try {
                $query = file_get_contents($value['path']);

                Adapter::exec($query);
                unset($data[$key]);

                $this->migrationLog($value['file'], $value['path'], $value['version'], 'Y', $query);

            } catch (\Exception $e) {

                $this->migrationLog($value['file'], $value['path'], $value['version'], 'N', '', $e->getMessage());

            }
        }
    }

    private function migrationLog(
        ?string $file = '',
        ?string $fullpath = '',
        ?string $version = '',
        ?string $completed = '',
        ?string $query = '',
        ?string $error = ''
    ): void {
        $db = Adapter::dispense('migration');
        $db->file = $file;
        $db->version = $version;
        $db->completed = $completed;

        if ($query) {
            $db->query = $query;
        }

        if ($error) {
            $db->fail = $error;
        }

        $db->fullpath = $fullpath;

        Adapter::store($db);
    }
}
