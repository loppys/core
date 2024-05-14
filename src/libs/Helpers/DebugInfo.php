<?php

namespace Vengine\libs\Helpers;

use Vengine\System\DefaultController;

class DebugInfo extends DefaultController
{
    public function indexAction(): void
    {
        $this->app->callModule('_debug_');
    }
}
