<?php

namespace Vengine;

use Vengine\libs\DataBase\Adapter;
use Vengine\libs\TemplateVar;
use Vengine\Render\RenderPage;

/**
 * Ядро!
 */
class Process
{

	/**
	 * @str
	 */
	public $moduleName = 'core';


	/**
	 * @str
	 */
	public $page;

	/*
	* @array
	* @str
	*/
	protected $errors = [];

	/*
	* @array
	* @str
	*/
	protected $connect_string;

	/*
	* @array
	* @str
	*/
	protected $dblogin;

	/*
	* @array
	* @str
	*/
	protected $dbpassword;

	/*
	* @array
	* @str
	*/
	protected $dbname;

	/*
	* @array
	* @str
	*/
	protected $dbtables = 'users';

	/*
	* @array
	* @str
	*/
	protected $standard_currency;

	/*
	* @bool
	*/
	public $engineApi;

	/*
	* @array
	* @str
	*/
	protected $template;

	/*
	* @array
	* @int
	*/
	protected $level_access;

	/*
	* @array
	* @string
	*/
	protected $product_db = 'product';

	/*
	* @array
	* @string
	*/
	public $session_name = 'logged_user';

	/*
	 * Наименование страницы
	 * @string
	 */
	 public $namePage;

		// <-start debug param->
		// временная мера, пока нет модуля licence и install
	 /**
	  * версия
		* !debug
	  */
		public $version = '0.5.0';

		/**
 	  * глобальная версия
 		* !debug
 	  */
		public $global_version = '0.5';

		/**
 	  * версия
		* !debug
 	  */
 		public $build_date = '16.02.2022';
		// <-end debug param->

		public $cache;

		public $tmpfile;

	/*
	* Конструктор класса
	*/
	function __construct($connect_string, $dblogin, $dbpassword)
	{
		$this->setConfig();

		$this->tmpfile = $_SERVER['DOCUMENT_ROOT'] . '/_tmp/';

		$this->connect_string = $connect_string;
		$this->dblogin = $dblogin;
		$this->dbpassword = $dbpassword;

		if (!Adapter::testConnection()) {
			$this->databaseConnect(
				$this->connect_string,
				$this->dblogin,
				$this->dbpassword
			);
		}

		$this->pageFix();
		$this->sessionStart();
		$this->debugMode();
		$this->logout();

		if (empty($_SESSION[$this->session_name]) && !stristr($this->page, 'vengine/api/')) {
			$this->guestid();
		}
	}

	public function setConfig() //Выделить под это действие отдельный класс и сделать независимую запись свойств
	{
		require _File('config', 'config');

		foreach ($config as $key => $value) {
			if (property_exists(__CLASS__, $key)) {
				$this->$key = $value;
			}
		}
	}

	public function init($closed = false)
	{
		require _File('settings', 'config');

		$this->moduleConnect($modules);
		$this->serviceConnect($services);

		if (!Adapter::testConnection() || ($closed && !$_GET['debug:__sys__'])) {
			print 'На сайте ведутся технические работы, попробуйте вернуться позже!';
			return;
		}

		$this->pageNavigation();
	}

	/**
	 * Записывает в бд данные!
	 */
	 public function dbSave($table, array $fields)
	 {
	 		if ($table && $fields) {
						$db = Adapter::dispense($table);

						foreach ($fields as $keyField => $fieldValue) {
							$db->$keyField = $fieldValue;
							continue;
						}

						!empty($db->$keyField) ? R::store($db) : '';
	 		}
	 }

	/**
	 * Дебаг мод
	 */
	public function debugMode()
	{
		if ($_GET['__DEBUG_MODE'] == 'IGNORE') {
			$this->build = substr(md5($this->version), 7, -17);
			$this->reference = substr(md5($global_version), 7, -17);

			print(
				'Версия vEngine: ' . $this->version . '<br>' .
				'Сборка: ' . $this->build . '<br>' .
				'Референс сборки: ' . $this->reference . '<br>' .
				'Дата сборки: ' . $this->build_date
			);
		}
	}

	/**
	 * возвращает таблицу DB
	 */
	public function returnSringDB()
	{
			return $this->dbtables;
	}

	/*
	* Запуск сессии
	*/
	public function sessionStart()
	{
		if (empty($_SESSION[$this->session_name])) {
			session_start();
		}
	}

	/*
	* Ошибка 404
	*/
	public function error404()
	{
		exit(include 'template/error404.tpl.php');
	}

	/*
	* редиректы!
	*/
	public function redirect($url = '')
	{
		print "<script>self.location='".$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/'.$url."';</script>";
	}

	/*
	 * Редирект с задержкой на другие Url (Не этого сайта)
	 * Задел на будущее
	 */
	 public function redirectToAnotherUrl($value='')
	 {
	 	// к 0.5 сделать
	 }

	 public function returnSettigns()
	 {
	 	require $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';

		$setting['modules'] = $modules;
		$setting['services'] = $services;
		$setting['settings'] = $settings;

		return $setting;
	 }

	/*
	* @deprecated
	*/
	public function tr($text = '', $translate = '')
	{
		tr($text, $translate);
	}

	public function pageFix()
	{
		#Исправление ссылок
		if ($_SERVER['REQUEST_URI'] == '/') {
			$this->page = 'home';
		}else{
			$this->page = substr($_SERVER['REQUEST_URI'], 1);
		}

		// Исправление $_GET запросов
		if ($_GET) {
			$getString = substr($this->page, strrpos($_SERVER['REQUEST_URI'], '?'), 1000);
			$leghtFix = strlen($getString);
			$leghtFullPage = strlen($this->page);

			$getResult = $leghtFullPage - ( $leghtFix + 1 );

			$result = mb_substr($this->page, 0, $getResult);

			if (empty($result)) {
				$this->page = 'home';
			}else{
				$this->page = $result;
			}
		}

			return $this->page;
	}

	/**
	 * @return $page
	 */
	public function returnPage()
	{
		return $this->page;
	}

	/*
	* Поиск и подключение файлов в указанной папке
	*/
	public function findFiles($page, $dir)
	{
		$pageDir = $_SERVER['DOCUMENT_ROOT'] . '/' . $dir . '/';
		$pageRoad = scandir($pageDir);

		#Удаление не нужных элементов
		$f = array_slice($pageRoad, 3);

			foreach ($f as $i) {
				$fix = substr($i, strrpos($i, '.') + 1);

				#Если нашёл в основной папке, то подключает
				if ($fix == 'php' && $i == $page . '.php') {
					if (file_exists($pageDir . $i)) {
						$s = true;
						return include $pageDir . $i;
					}
				}

				#Если скрипт не обнаружил файлов.
				#Начнёт искать в подпапках
				if (!$s && $fix != 'php') {
					$folder = $pageDir . $i . '/';
					$pageRoad = scandir($folder);
					$f = array_slice($pageRoad, 3);

					foreach ($f as $i) {
						$fix = substr($i, strrpos($i, '.') + 1);

						if ($fix == 'php' && $i == $page . '.php') {
							if (file_exists($folder . $i)) {
								$s = true;
								return include $folder . $i;
							}
						}
					}
				}
			}
		}

	/**
	 * Подключение сервисов
	 */
	public function serviceConnect($services)
	{
		if (!$services) {
			return;
		}

		while ($name = current($services)) {
			$key = key($services);

			$file = array_shift($services[$key]);

			$dir[] = 'modules/services/' . $key;

			foreach ($dir as $value) {
				foreach ([$file] as $connect) {
					if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $value . '/' . $connect . '/' . $connect . '.php')) {
						include $_SERVER['DOCUMENT_ROOT'] . '/' . $value . '/' . $connect . '/' . $connect . '.php';
					}
				}
			}

		  next($services);
		}
	}

	/*
	* Навигация страниц
	*/
	public function pageNavigation()
	{
		new RenderPage($this);
	}

	/*
	* Подключение к базе данных
	*/
	public function databaseConnect($connect_string, $dblogin, $dbpassword)
	{
		Adapter::connect($connect_string, $dblogin, $dbpassword);
	}


	/*
	* Подключение стандартных модулей
	*/
	public function moduleConnect($modules)
	{
		foreach ($modules as $value) {
			$this->findFiles($value, 'modules');
		}
	}


	/*
	* Подключение шаблонов
	*/
	public function templateConnect($template, $type = 'template')
	{
		$path = [
			'core' => '/core/template/',
			'template' => '/template/'
		];

		$template = 'file::' . $path[$type] . $template;

		return $template;
	}


	#Создание временного пользователя
	public function guestid()
	{
		$sess = $_SESSION[$this->session_name]->login;
		if (empty($_COOKIE['guestid']) && empty($sess)) {
			setcookie('guestid', substr(md5(rand(0, 50000)), 25));
			$this->redirect('');
		}
		if (!empty($sess)) {
			unset($_COOKIE['guestid']);
			// сделать запись в бд, если её нет и находится на регистрации
		}
	}


	/*
	* Кастомные модули добавлять аккуратно!
	*/
	public function moduleCustom($custom, $name)
	{
		if (!empty($custom) && $name != '') {
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/www/_modules/' . $name . '/' . $custom . '.module.php') && $custom != '') {
				require $_SERVER['DOCUMENT_ROOT'] . '/www/_modules/' . $name . '/' . $custom . '.module.php';
			}
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
	}

	protected function getVars()
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

	 public function logout()
	 {
		 if ($this->page == 'logout') {
		 	unset($_SESSION[$this->session_name]);
		 	$this->redirect('');
		}elseif (!empty($_POST['logout'])) {
		 	unset($_SESSION[$this->session_name]);
		 	$this->redirect('');
		}elseif(!empty($_GET['logout'])) {
			unset($_SESSION[$this->session_name]);
			$this->redirect('');
		}
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
