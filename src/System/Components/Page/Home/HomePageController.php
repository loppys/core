<?php

namespace Vengine\System\Components\Page\Home;

use Vengine\System\Components\Page\AbstractPageController;

class HomePageController extends AbstractPageController
{
    public function indexAction(): void
    {
        print <<<HTML
<title>Home | vEgnine</title>
vEngine установлен! 
<br>
<a href="https://doc.vengine.ru/" target="_blank">Документация</a>
HTML;
    }
}
