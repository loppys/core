<?php

namespace Vengine;

use Doctrine\DBAL\Connection;
use Loader\System\Container;
use Loader\System\Interfaces\ContainerInjection;
use Vengine\Cache\CacheManager;
use Vengine\Packages\Updater\Components\Configurator;
use Vengine\Packages\User\Entity\User;
use Vengine\System\Actions;
use Vengine\System\Components\Page\Render;
use Vengine\System\Config\AppConfig;
use Vengine\System\Controllers\Router;
use Vengine\System\Database\SystemAdapter;
use Vengine\System\Settings\Structure;

/**
 * @property-read SystemAdapter adapter
 * @property-read Startup startup
 * @property-read Structure structure
 * @property-read Container container
 * @property-read Render render
 * @property-read Router router
 * @property-read Configurator configurator
 * @property-read User user
 * @property-read AppConfig config
 * @property-read Actions $actions
 * @property-read Connection $db
 * @property-read CacheManager $cache
 */
interface Injection extends ContainerInjection
{

}
