<?php

namespace Vengine\System\Components\Page\Home;

use Vengine\System\Components\Page\AbstractPageController;

class HomePageController extends AbstractPageController
{
    protected string $title = 'Home | vEgnine';

    public function prepareData(): void
    {
        print <<<HTML
vEngine установлен! 
<br>
<a href="https://doc.vengine.ru/" target="_blank">Документация</a>
HTML;
    }
}
