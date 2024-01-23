<?php

namespace Vengine\Packages\Updater\Controllers;

use Vengine\App;
use Vengine\libs\Helpers\Crypt;
use Vengine\Packages\Updater\Components\Configurator;
use Vengine\System\Actions;
use Vengine\System\Components\Database\Adapter;
use Vengine\System\Components\Page\AbstractPageController;
use Vengine\System\Components\Page\Render;
use Vengine\System\Controllers\Router;

class UpdaterPageController extends AbstractPageController
{
    protected Configurator $configurator;

    public function __construct(
        Adapter $adapter,
        Render $render,
        Router $router,
        Actions $actions,
        Configurator $configurator
    ) {
        $this->adapter = $adapter;
        $this->render = $render;
        $this->router = $router;
        $this->actions = $actions;
        $this->configurator = $configurator;

        $this->request = App::getRequest();
        $this->session = $this->request->getSession();

        $this->prepareData();
    }

    public function prepareData(): void
    {
        $post = $this->request->request->all();

        if (!empty($post)) {
            $data = [
                'app' => [
                    'project' => $post['project'],
                    'uuid' => $post['uuid'],
                    'key' => $post['key'],
                    'root' => password_hash($post['root'], PASSWORD_DEFAULT),
                    'crypt' => $post['crypt'] === 'on',
                ],
                'database' => [
                    'dbType' => $post['dbType'],
                    'dbHost' => $post['dbHost'],
                    'dbName' => $post['dbName'],
                    'dbLogin' => $post['dbLogin'],
                    'dbPassword' => $post['dbPassword'],
                ],
                'services' => [
                    'token' => $post['token']
                ],
            ];

            if ($data['app']['crypt']) {
                foreach ($data['database'] as $key => $item) {
                    $data['database'][$key] = Crypt::dsEncrypt($item);
                }

                $data['app']['uuid'] = Crypt::dsEncrypt($data['app']['uuid']);
                $data['app']['key'] = Crypt::dsEncrypt($data['app']['key']);
                $data['services']['token'] = Crypt::dsEncrypt($data['app']['key']);
            }

            $this->configurator->setConfig($data);

            $this->redirect('/');
        }

        $this->renderPage();
    }

    private function renderPage(): void
    {
        $templateFolder = $this->render->getTemplateFolder() . 'system/';

        if (!is_dir($templateFolder)) {
            @mkdir($templateFolder);
        }

        $tpl = dirname(__DIR__);

        $tpl .= DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR;

        if (is_dir($templateFolder)) {
            if (!file_exists($templateFolder . 'update.php')) {
                copy($tpl . 'update.php', $templateFolder . md5('update') . '.php');
            }

            if (!file_exists($templateFolder . 'install.php')) {
                copy($tpl . 'install.php', $templateFolder . md5('install') . '.php');
            }
        }

        $this->render->setTitle('Базовая установка')
            ->setTemplate('system/' . md5('install') . '.php');

        $this->render->addStyle('https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css');

        $this->render->initJsPath('https://code.jquery.com/jquery-3.2.1.slim.min.js')
            ->initJsPath('https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js');
    }
}
