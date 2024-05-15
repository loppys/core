<?php

namespace Vengine\Packages\Migrations\Adapters;

use Vengine\App;
use Doctrine\DBAL\Exception;
use Vengine\Packages\Migrations\DTO\MigrationResult;
use Vengine\Packages\Migrations\Interfaces\AdapterSQLInterface;
use Vengine\Packages\Migrations\Interfaces\MigrationAdapterInterface;
use Vengine\System\Exceptions\AppException;

class AdapterSQL implements MigrationAdapterInterface, AdapterSQLInterface
{
    protected array $result = [];

    /**
     * @throws AppException
     * @throws Exception
     */
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

                App::app()->db->executeQuery($query);

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
