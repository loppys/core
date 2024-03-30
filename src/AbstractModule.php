<?php

namespace Vengine;

use Symfony\Component\HttpFoundation\Session\Session;
use Vengine\Packages\Migrations\Interfaces\MigrationManagerInterface;
use Vengine\System\Components\Database\Adapter;
use Vengine\System\Database\SystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Vengine\System\Interfaces\AppConfigInterface;
use Vengine\System\Traits\ContainerTrait;
use Vengine\System\Config\AppConfig;

abstract class AbstractModule extends AbstractConfig implements Injection
{
    use ContainerTrait;

    protected AppConfig $interface;

    protected MigrationManagerInterface $migrationManager;

    protected Request $request;

    protected Session $session;

    protected string $module;

    protected string $version;

    public function __construct()
    {
        $this->container = $this->getContainer();

        $this->interface = $this->container->createObject(AppConfigInterface::class);

        $this->request = $this->getRequest();
        $this->session = $this->getSession();

        $this->setInterface();
        $this->setConfigVar();

        $this->migrationManager = $this->container->createObject(MigrationManagerInterface::class);

        $defaultConfig = $this->interface->defaults;

        if ($defaultConfig) {
            if ($defaultConfig[$this->module]) {
                foreach ($defaultConfig[$this->module] as $key => $value) {
                    $this->interface->$key = $value;
                }
            }

            unset($this->interface->defaults);
        }
    }

    public function getDatabaseAdapter(): SystemAdapter
    {
        return $this->db;
    }

    /**
     * @deprecated
     */
    public function getAdapter(): Adapter
    {
        return $this->adapter;
    }

    public function getRequest(): Request
    {
        return App::getRequest();
    }

    public function getSession(): Session
    {
        return App::getSession();
    }

    public function getInterface(): AppConfig
    {
        return $this->interface;
    }

    public function setConfigVar(): void
    {
        $query = <<<SQL
SELECT *
FROM `cfg`
SQL;

        $result = $this->adapter::getAll(
            $query
        );

        foreach ($result as $value) {
            if (!$this->interface->{$value['cfg_name']}) {
                $this->interface->{$value['cfg_name']} = $value['cfg_value'];
            }
        }
    }

    public function setInterface(): void
    {
        $userConfig = require $this->structure->userConfig . 'config.php';
        $config = require('config/config.php');

        foreach ((array)$userConfig as $uk => $uv) {
            if (array_key_exists($uk, $config)) {
                $config[$uk] += $uv;
            } else {
                $config[$uk] = $uv;
            }
        }

        if ($config['defaults']) {
            foreach ($config['defaults'] as $dk => $dv) {
                if (!$dv['require']) {
                    break;
                }

                foreach ($dv['require'] as $rk => $rv) {
                    $requirePath = $this->getRequirePath($rv);

                    if ($requirePath === 'run') {
                        if (file_exists($requirePath)) {
                            require_once($requirePath);
                        }

                        continue;
                    }

                    if (file_exists($requirePath)) {
                        $config['defaults'][$dk][$rk] = require_once($requirePath);
                    }
                }
            }
        }

        foreach ($config as $key => $value) {
            $this->interface->$key = $value;
        }
    }

    private function getRequirePath(array $arr): string
    {
        $structure = true;
        $path = '';

        foreach ($arr as $key => $value) {
            if (empty($key)) {
                $structure = false;

                break;
            }

            $path = $this->structure->{$key} . $value;
        }

        if ($structure === false) {
            return 'run';
        }

        return $path;
    }
}
