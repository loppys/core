<?php

namespace Vengine;

use Vengine\libs\Template\TemplateVar;
use Vengine\AbstractModule;
use Vengine\Controllers\Routing\PageController;
use Vengine\libs\Exception\HttpException;
use System\modules\Api;

class Base extends AbstractModule
{
	public $module = 'Core';
	public $version = '1.0.0-Alpha';

	/*
	* Конструктор класса
	*/
	function __construct()
	{
		parent::__construct();

		$this->uriParser();
		$this->sessionStart();
		$this->debugMode();

		if ($_GET['_DEBUG_MODE'] === 'SQL') {
			$this->adapter->fancyDebug();
		}

		if (empty($_SESSION['user']) && !stristr($this->interface->page, 'vengine/api/')) {
			$this->guestid();
		}
	}

	public function uriParser(): void
	{
		$request = $this->request;

		$this->interface->uri = [
			'requestUri' => $request->getRequestUri(),
			'path' => $request->getPathInfo(),
			'scheme' => $request->getScheme(),
			'host' => $request->getHttpHost(),
			'method' => $request->getMethod(),
		];

		$this->interface->page = $this->interface->uri['path'];
	}

	public function run($localPages = null): void
	{
		$this->interface->localPages = $localPages;

		if (!$this->adapter->testConnection() || $this->interface->closed) {
			print 'На сайте ведутся технические работы, попробуйте вернуться позже!';
			return;
		}

		$this->pageNavigation();
	}

	/**
	 * Дебаг мод
	 */
	public function debugMode(): void
	{
		if ($this->request->get('__DEBUG_INFO') == 'ENGINE') {
			$info = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/composer.lock'));

			foreach ($info->packages as $key => $value) {
				if ($value->name === 'vengine/core') {
					$info = $info->packages[$key];
				}
			}

			$date = date_create($info->time);

			$key = $this->interface->project['key'];

			if ($key) {
				$key = preg_replace("/[^\d]/", '*', $key);
			} else {
				$key = 'EMPTY';
			}

			print(
				'<div style="
				position:fixed;
				border: 5px solid rgba(0,0,0,0.12);
				border-radius: 2px;
				background: white;
				padding: 4px;
				z-index: 999999;
				">
				Версия ядра: ' . $info->version . '<br>' .
				'<br>' .
				'Временная папка: ' . $this->interface->tmpDir . '<br>' .
				'Папка проекта: ' . $this->interface->projectDir . '<br>' .
				'<hr>' .
				'Ключ: ' . $key . '<br>' .
				'Название проекта: ' . $this->interface->project['nameProject'] . '<br>' .
				'<hr>' .
				'Референс: ' . $info->source->reference . '<br>' .
				'Дата установки: ' . date_format($date, "Y/m/d H:i:s")
				. '</div>'
			);
		}
	}

	/*
	* Запуск сессии
	*/
	public function sessionStart(): void
	{
		if (empty($_SESSION['_start'])) {
			session_start();
			$_SESSION['_start'] = true;
		}
	}

	/*
	* редиректы!
	*/
	public function redirect($url = '')
	{
		print "<script>self.location='".$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/'.$url."';</script>";
	}

	/*
	* @deprecated
	*/
	public function tr($text = '', $translate = '')
	{
		tr($text, $translate);
	}

	public function pageFix(): void
	{
		if ($this->interface->uri['path'] == '/') {
			$page = 'home';
		}else{
			$page = substr($this->interface->uri['path'], 1);
		}

		$this->interface->page = $page;
	}

	/*
	* Навигация страниц
	*/
	public function pageNavigation()
	{
		new PageController($this);
	}

	/*
	* Подключение шаблонов
	*/
	public function templateConnect($template, $type = 'WWW')
	{
		$dir = dirname(dirname(__FILE__));

		if (!$this->interface->structure['template']) {
			$templatePath = $this->interface->structure['www'] . '_template/';
		} else {
			$templatePath = $this->interface->structure['template'];
		}

		$path = [
			'CORE' => $dir . '/src/template/',
			'WWW' => $templatePath
		];

		$template = 'file::' . $path[$type] . $template;

		return $template;
	}


	#Создание временного пользователя
	public function guestid()
	{
		$sess = $this->session['user']->login;
		if (empty($_COOKIE['guestid']) && empty($sess)) {
			setcookie('guestid', substr(md5(rand(0, 50000)), 25));
			$this->redirect('');
		}
		if (!empty($sess)) {
			unset($_COOKIE['guestid']);
			// сделать запись в бд, если её нет и находится на регистрации
		}
	}

	//Переделать под массовое присвоение и перенести в либы
	protected function setVar()
	{
		TemplateVar::set([
			'activeAutch' => isActiveModule('Autch'),
			'userLogin' => $_SESSION[$this->session_name]->login,
			'guestid' => $_COOKIE['guestid']
		]);

		return $this;
	}

	protected function getVars(): array
	{
		$this->setVar();

		return TemplateVar::getAll();
	}

	/**
	 * Подключение js скриптов
	 */
	 public function addScript($js = [], $custom = false)
	 {
		 if ($js && !$custom) {
			 foreach ($js as $value) {
				 $result[] = '<script src="' . $value . '"></script>';
			 }
		 } else {
			 foreach ($js as $value) {
				 $result[] = '<script ' . $value . '></script>';
			 }
		 }

		 return $result;
	 }

	 public function addStandartJS(): array
	 {
	 	return $this->addScript(
			[
				'src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
			 	integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
			 	crossorigin="anonymous"',
			 	'src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
			 	integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
			 	crossorigin="anonymous"',
			 	'src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
			 	integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
			 	crossorigin="anonymous"'
		 	], true
		);
	 }

}
