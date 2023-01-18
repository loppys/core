<?php

namespace Vengine\Packages\Updater\Components;

use Vengine\System\Components\Page\Render;
use Vengine\System\Controllers\Router;
use Vengine\System\Exceptions\PageNotFoundException;

class RenderUpdater extends Render
{
    public function process(): void
    {
        $install = false;

        $templateFolder = $this->getTemplateFolder() . 'system/';

        if (!is_dir($templateFolder)) {
            @mkdir($templateFolder);
        }

        $tpl = dirname(__DIR__, 1);
        $tpl .= DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR;

        if (is_dir($templateFolder)) {
            if (!file_exists($templateFolder . 'update.php')) {
                copy($tpl . 'update.php', $templateFolder . md5('update') . '.php');
            }

            if (!file_exists($templateFolder . 'install.php')) {
                copy($tpl . 'install.php', $templateFolder . md5('install') . '.php');
            }
        }

        $root = $_SERVER['DOCUMENT_ROOT'];

        $packageList = require(
            $root . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'composer' . DIRECTORY_SEPARATOR . 'installed.php'
        );

        $versionList = $packageList['versions'];

        $coreInfo = $versionList['vengine/core'];
        $renderInfo = $versionList['vengine/render'];
        $loaderInfo = $versionList['vengine/loader'];

        $this->setVariableByName(
            'core', 'core ' . $coreInfo['pretty_version'] . ' - ' . $coreInfo['reference']
        );

        $this->setVariableByName(
            'render', 'render ' . $renderInfo['pretty_version'] . ' - ' . $renderInfo['reference']
        );

        $this->setVariableByName(
            'loader', 'loader ' . $loaderInfo['pretty_version'] . ' - ' . $loaderInfo['reference']
        );

        if (!$install) {
            $this->setTitle('Базовая установка')
                ->setTemplate('system/' . md5('install') . '.php');
        } else {
            $this->setTitle('Обновление настроек')
                ->setTemplate('system/' . md5('update') . '.php');
        }

        $this->addStyle('https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css');

        $this->initJsPath('https://code.jquery.com/jquery-3.2.1.slim.min.js')
            ->initJsPath('https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js');

        $this->render();
    }

    /**
     * @param int $step
     *
     * @throws PageNotFoundException
     */
    public function changeStep(int $step): void
    {
        if ($step > 3 || $step === 0) {
            Router::pageNotFound();
        }

        $this->renderStep($step);
    }

    protected function renderStep(int $step): void
    {
        $this->render();
    }
}