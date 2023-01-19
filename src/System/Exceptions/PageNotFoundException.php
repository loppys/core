<?php

namespace Vengine\System\Exceptions;

use Throwable;
use Vengine\System\Components\Page\Render;

class PageNotFoundException extends AppException
{
    public function __construct($message = "", $code = 404, Throwable $previous = null)
    {
        $tpl = dirname(__DIR__, 1) . '/template/404/index.php';
        $tplStyle = dirname(__DIR__, 1) . '/template/404/style.css';

        $render = Render::getInstance();
        $tplFolder = $render->getTemplateFolder() . 'system/';

        if (!is_dir($tplFolder)) {
            @mkdir($tplFolder);
        }

        if (is_dir($tplFolder)) {
            if (!file_exists($tplFolder . md5('404') . '.php')) {
                copy($tpl, $tplFolder . md5('404') . '.php');
            }

            if (!file_exists($tplFolder . md5('404') . '.css')) {
                copy($tplStyle, $tplFolder . md5('404') . '.css');
            }
        }

        $render->setVariableByName('h1', $code)
            ->setVariableByName('h3', $message ?: 'Страница не найдена!')
            ->setVariableByName(
                'text',
                'Страница заблокирована, либо её не существует, пожалуйста проверьте правильность адреса.'
            )
            ->setVariableByName('textLink', 'Вернуться на главную')
        ;

        $render->addStyle('/www/template/system/' . md5('404') . '.css')
            ->addStyle('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css')
            ->addMetaData('name', 'viewport', 'width=device-width, initial-scale=1');

        $render->runTemplate('system/' . md5('404') . '.php');

        parent::__construct($message, $code, $previous);
    }
}