<?php

namespace Vengine;

use Loader\System\Builder;
use Loader\System\Container;
use Loader\System\Interfaces\ContainerInjection;
use Vengine\Packages\Updater\Components\Configurator;
use Vengine\Packages\User\Entity\User;
use Vengine\System\Components\Database\Adapter;
use Vengine\System\Components\Page\Render;
use Vengine\System\Controllers\Router;
use Vengine\System\Settings\Structure;

/**
 * @property Adapter adapter
 * @property Startup startup
 * @property Structure structure
 * @property Container container
 * @property Builder builder
 * @property Render render
 * @property Router router
 * @property Configurator configurator
 * @property User user
 */
interface Injection extends ContainerInjection
{

}