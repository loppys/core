<?php

namespace Vengine\Packages\Migrations\Adapters;

use Vengine\App;
use RuntimeException;
use Vengine\Packages\Migrations\DTO\MigrationResult;
use Vengine\Packages\Migrations\Interfaces\AdapterSQLInterface;
use Vengine\Packages\Migrations\Interfaces\MigrationAdapterInterface;

class AdapterSQL implements MigrationAdapterInterface, AdapterSQLInterface
{
    /**
     * @var array
     */
    protected $result = [];

    public function run(array $fileList): MigrationAdapterInterface
    {
        if (empty($fileList)) {
            return $this;
        }

        foreach ($fileList as $info) {
            if (!file_exists($info['path'])) {
                continue;
            }

            $pathInfo = pathinfo($info['path']);

            if ($pathInfo['extension'] === 'sql') {
                $query = file_get_contents($info['path']);

                $result = new MigrationResult();
                $result->setFile($pathInfo['basename']);

                try {
                    App::app()->adapter::exec($query);
                } catch (RuntimeException $e) {
                    $result->setError($e->getMessage());
                }

                $this->result[] = $result;
            }
        }

        return $this;
    }

    public function getResult(): array
    {
        return $this->result;
    }
}
