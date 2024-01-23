<?php

namespace Vengine\Packages\Migrations\Adapters;

use Vengine\Packages\Migrations\Interfaces\AdapterPHPInterface;
use Vengine\Packages\Migrations\Interfaces\MigrationAdapterInterface;
use Vengine\Packages\Migrations\Parts\Migration;

class AdapterPHP implements MigrationAdapterInterface, AdapterPHPInterface
{
    protected array $result = [];

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

            if ($pathInfo['extension'] === 'php') {
                $class = require($info['path']);

                if (!is_object($class)) {
                    continue;
                }

                if (!$class instanceof Migration) {
                    continue;
                }

                $this->result[] = $class->run();
            }
        }

        return $this;
    }

    public function getResult(): array
    {
        return $this->result;
    }
}
