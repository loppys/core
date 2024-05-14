<?php

namespace Vengine\Packages\Updater\Components;

use Vengine\libs\Helpers\Crypt;
use Vengine\Packages\Settings\Storage\ConstStorage;
use Vengine\System\Settings\Structure;

class Configurator
{
    protected array $config = [];

    private bool $decrypted = false;

    protected static string $path;

    public function __construct(Structure $structure)
    {
        static::$path = $this->config['path'] = $structure->userConfig
            . md5($_SERVER['SERVER_NAME'])
            . '.'
            . ConstStorage::DEFAULT_CONFIG_NAME;

        $this->initConfig();
    }

    public static function getConfigPath(): string
    {
        return static::$path;
    }

    protected function initConfig(): void
    {
        if (file_exists($this->config['path'])) {
            $data = file_get_contents($this->config['path']);

            $this->config['data'] = unserialize($data);
        }
    }

    public function replaceConfig(array $data): Configurator
    {
        $config = $this->getConfig();

        foreach ($data as $key => $value) {
            if (!empty($config[$key])) {
                $config[$key] = $value;
            }
        }

        $result = serialize($config);

        if (file_exists($this->config['path'])) {
            file_put_contents($this->config['path'], $result);

            $this->config['data'] = $data;
        }

        return $this;
    }

    public function setConfig(array $data): Configurator
    {
        $result = serialize($data);

        if (!file_exists($this->config['path'])) {
            file_put_contents($this->config['path'], $result);
        }

        return $this;
    }

    public function getConfig(): array
    {
        if ($this->config['data']['app']['crypt'] && !$this->decrypted) {
            $this->config['data'] = array_map(static function ($item) {
                foreach ($item as $key => $value) {
                    if ($key === 'project' || $key === 'crypt' || $key === 'root') {
                        continue;
                    }

                    $item[$key] = Crypt::dsDecrypt($value);
                }

                return $item;
            }, $this->config['data']);

            $this->decrypted = true;
        }

        return $this->config['data'] ?: [];
    }
}
