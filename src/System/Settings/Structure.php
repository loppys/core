<?php

namespace Vengine\System\Settings;

use Vengine\AbstractConfig;

/**
 * @property string vendor
 * @property string project
 * @property string container
 * @property string coreConfig
 * @property string userConfig
 * @property string logs
 * @property string core
 * @property string api
 * @property string uApi
 * @property string userMigrations
 * @property string coreMigrations
 */
class Structure extends AbstractConfig
{
    public function __construct()
    {
        $this->setDefaultStructure();
    }

    protected function setDefaultStructure(): static
    {
        $config = require $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
        $coreConfig = require $_SERVER['DOCUMENT_ROOT'] . '/vendor/vengine/core/src/config/config.php';

        if (empty($config['structure'])) {
            $config['structure'] = $this->getDefaultFolderStructure();
        }

        foreach ($config as $k => $v) {
            foreach ($coreConfig as $ck => $cv) {
                if (array_key_exists($ck, $config)) {
                    $config[$ck] += $cv;
                } else {
                    $config[$ck] = $cv;
                }
            }
        }

        $projectDir = $_SERVER['DOCUMENT_ROOT'] . '/';

        $path = [
            'ROOT:' => $projectDir
        ];

        foreach ($config['structure'] as $sKey => $sValue) {
            $name = strtoupper(stristr($sValue, ':', true)) . ':';
            $tempPath = substr(stristr($sValue, ':'), 1);

            $parent = array_key_exists($name, $path);

            if ($parent) {
                $replace = [
                    $name => $path[$name]
                ];

                $result = strtr($name, $replace) . $tempPath;

                $path[strtoupper($sKey) . ':'] = $result;
                $this->$sKey = $result;
            }
        }

        return $this;
    }

    private function getDefaultFolderStructure(): array
    {
        return [
            'project' => 'ROOT:',
            'tmp' => 'PROJECT:_tmp/',
            'www' => 'PROJECT:www/',
            'migrations' => 'PROJECT:Migrations/',
            'logs' => 'PROJECT:logs/',
            'config' => 'PROJECT:config/'
        ];
    }
}
