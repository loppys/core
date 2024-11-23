<?php

namespace Vengine;

use Symfony\Component\HttpFoundation\Session\Session;
use Vengine\Cache\Drivers\ConfigCacheDriver;
use Vengine\Packages\Migrations\Interfaces\MigrationManagerInterface;
use Vengine\Packages\Modules\Interfaces\InfoInterface;
use Vengine\Packages\Modules\ModuleInfo;
use Vengine\System\Database\SystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Vengine\System\Exceptions\AppException;
use Vengine\System\Interfaces\AppConfigInterface;
use Vengine\System\Interfaces\ModuleInterface;
use Vengine\System\Traits\ContainerTrait;
use Vengine\System\Config\AppConfig;

abstract class AbstractModule extends AbstractConfig implements Injection, ModuleInterface
{
    use ContainerTrait;

    protected AppConfig $interface;

    protected MigrationManagerInterface $migrationManager;

    protected Request $request;

    protected Session $session;

    protected string $module;

    protected string $version;

    protected InfoInterface $info;

    protected ConfigCacheDriver $configCacheDriver;

    protected bool $cacheEnabled = true;

    protected string $template = 'default.php';

    public function __construct()
    {
        $this->container = $this->getContainer();
        $this->configCacheDriver = $this->cache->config;

        $this->configCacheDriver->getConfig()->setEnabled($this->cacheEnabled);

        if (empty($this->module)) {
            $this->module = static::class;
        }

        if (empty($this->info)) {
            $this->info = new ModuleInfo($this->module ?? 'undefined');

            $this->info
                ->setVersion($this->version ?? '0.0.0')
                ->setDeveloper(':3')
            ;
        }

        $this->request = $this->getRequest();
        $this->session = $this->getSession();

        $this->migrationManager = $this->container->createObject(MigrationManagerInterface::class);

        $cachedConfig = $this->configCacheDriver->get("{$this->module}_config");
        if (!empty($cachedConfig)) {
            $this->interface = $cachedConfig;
        } else {
            $this->interface = $this->container->createObject(AppConfigInterface::class);

            $this->setInterface();
            $this->setConfigVar();

            $defaultConfig = $this->interface->defaults;

            if ($defaultConfig) {
                if ($defaultConfig[$this->module]) {
                    foreach ($defaultConfig[$this->module] as $key => $value) {
                        $this->interface->$key = $value;
                    }
                }

                unset($this->interface->defaults);
            }

            $this->interface->_moduleName = $this->module;

            $this->configCacheDriver->set("{$this->module}_config", $this->interface);
        }

        $this->changeModuleInfo($this->info);

        $this->info->setLoaded();
    }

    public function getDatabaseAdapter(): SystemAdapter
    {
        return $this->adapter;
    }

    /**
     * @deprecated
     */
    public function getAdapter(): void
    {
        throw new AppException('getAdapter() deprecated. Use getDatabaseAdapter()');
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

        $result = $this->db->executeQuery($query)->fetchAllAssociative();

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

    public function getModuleName(): string
    {
        return $this->module;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function changeModuleInfo(InfoInterface $info): void
    {

    }

    public function getInfo(): InfoInterface
    {
        if (empty($this->info)) {
            $this->info = new ModuleInfo($this->module ?? 'undefined');

            $this->info
                ->setVersion($this->version ?? '0.0.0')
                ->setDeveloper(':3')
            ;
        }

        return $this->info;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function process(): static
    {
        return $this;
    }

    public function render(): void
    {
        $this->prepareRender();
        
        $this->render->runTemplate("{$this->module}/{$this->template}");
    }

    protected function prepareRender(): void
    {

    }
        
    protected function getToken(): ?string
    {
        return (string)$this->request->get('token')
            ?? (json_decode((string)$this->request->getContent())?->token ?? null)
            ?? $this->user->getToken()
            ?: null
        ;
    }
}
