<?php

namespace Vengine;

use Loader\System\Builder;
use Loader\System\Container;
use Loader\System\Interfaces\ContainerInjection;
use Vengine\System\Components\Database\Adapter;
use Vengine\System\Components\Page\Render;
use Vengine\System\Settings\Structure;

/**
 * @property Adapter adapter
 * @property Startup startup
 * @property Base base
 * @property Structure structure
 * @property Container container
 * @property Builder builder
 * @property Render render
 */
interface Injection extends ContainerInjection
{

}