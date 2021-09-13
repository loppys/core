<?php
/**
 * Ядро!
 */
class Process
{

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
	protected $standard_currency = 'RUB';

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
	* Русский текст
	* @string
	*
	*/
	protected $tr_ru;

	/*
	* Английский текст
	* @string
	*
	*/
	protected $tr_en;

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
		public $version = '0.4.10';

		/**
 	  * глобальная версия
 		* !debug
 	  */
		public $global_version = '0.4';

		/**
 	  * версия
		* !debug
 	  */
 		public $build_date = '12.09.2021';
		// <-end debug param->

	/*
	* Конструктор класса
	*/
	function __construct($connect_string, $dblogin, $dbpassword)
	{
		$this->connect_string = $connect_string;
		$this->dblogin = $dblogin;
		$this->dbpassword = $dbpassword;

		$this->pageFix();
		$this->sessionStart();
		$this->debugMode();
		$this->logout();

		if (empty($_SESSION[$this->session_name])) {
			$this->guestid();
		}

		if ($connect_string && !R::testConnection()) {
			$this->databaseConnect($this->connect_string, $this->dblogin, $this->dbpassword);
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
		exit(include 'template/tpl.error404.php');
	}

	/*
	* редиректы!
	*/
	public function redirect($url = '')
	{
		// exit('<meta http-equiv="refresh" content="0;url=' . $url . '">'); //Исправить это недоразумение

			ob_start();
			header("Location:" . $url);
			ob_end_flush();

			// http_redirect($url, NULL, NULL, HTTP_REDIRECT_PERM);
	}

	/*
	 * Редирект с задержкой на другие Url (Не этого сайта)
	 * Задел на будущее
	 */
	 public function redirectToAnotherUrl($value='')
	 {
	 	// к 0.5 сделать
	 }

	/*
	* Переводы
	*/
	public function tr($text = '', $translate = '')
	{
		// if ($this->tr_ru) {
		// 	return $text;
		// }
		// if ($this->tr_en) {
		// 	if (core::$translate == '') {
		// 		return $text;
		// 	}
		// 	return $translate;
		// }
		return $text;
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
	* Поиск и только поиск файлов в указанной папке
	*/
	public function returnFolderContents($dir, $print = false)
	{
		$pageDir = $_SERVER['DOCUMENT_ROOT'] . '/' . $dir . '/';
		$pageRoad = scandir($pageDir);

		#Удаление не нужных элементов
		$f = array_slice($pageRoad, 3);

			foreach ($f as $i) {
				$fix = substr($i, strrpos($i, '.') + 1);

				if ($fix == 'php' && $i != '_helpers.php' && $i != 'Install.php') {
					if (file_exists($pageDir . $i) && $print == false) {
						return $i;
					}elseif ($print === true) {
						print substr($i, 0, -4) . '<br>';
					}
				}
			}
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
		if ($this->page == 'admin') {
			include 'Admin/admin.tpl.php';
		}elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/install.init') && $this->page == 'install') {
			$this->moduleConnect(['Install']);
			returnMethod('Install', $this->page);
		}else{
			$this->renderPage($this->page);
		}
	}

	/**
	 * Метод генерации кнопок навигации в шапке
	 */
	public function navigationButton()
	{
		$countAll = R::count('pages');
		$getPage = R::findAll('pages');

		switch (true) {
			case $countAll > 5:
				for ($i=0; $i < $countAll; $i++) {
					if ($i != 5) {
						foreach ($getPage as $value) {
							if (
							$value['module'] != 'autch'
							&& $value['module'] != 'Profile'
							&& $value['custom_url'] != '#%api%#'
						) {
								print '<li class="hover-menu"><a href="' . $value['page'] . '" class="nav-link px-2 text-white">' . $value['name'] . '</a></li>';
							}
						}
					}

					if ($i >= 5) {
						print '<div class="dropdown show">
						  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    '. $this->tr('Ещё', 'More') .'
						  </a>

						  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';

							foreach ($getPage as $value) {
								if (
								$value['module'] != 'autch'
								&& $value['module'] != 'Profile'
								&& $value['custom_url'] != '#%api%#'
							)	{
									print '<a class="dropdown-item" href="' . $value['page'] . '">' . $value['name'] . '</a>';
								}
							}
							print '</div>
						</div>';
					}
				}
				break;
			case $countAll <= 5:
				foreach ($getPage as $value) {
					if (
					$value['module'] != 'autch'
					&& $value['module'] != 'Profile'
					&& $value['custom_url'] != '#%api%#'
				) {
						print '<li class="hover-menu"><a href="' . $value['page'] . '" class="nav-link px-2 text-white">' . $value['name'] . '</a></li>';
					}
				}
				break;

			default:
				print '<li class="hover-menu"><a href="" class="nav-link px-2 text-white">ERROR</a></li>';
				break;
		}
	}

	/*
	* Выводит сообщения об ошибках
	* Модальное окно либо починить, либо кильнуть
	*/
	public function renderError($errors = [], $modal = true)
	{
		if ($errors && $modal == false) {
		    foreach ($errors as $value) {

			      echo '<div class="alert alert-danger" role="alert">
			      <label>';
			      echo $value . '<br>';
			      echo '</label>
			      </div>';

		    }
		} else if ($errors && $modal == true) {
			print '<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="modalError" aria-hidden="true">
						<div class="modal-dialog modal-lg">
						<div class="modal-content">

									<div class="modal-header">
										<h4 class="modal-title" id="myLargeModalLabel">' . $this->tr('Ошибка', 'Error') . '</h4>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">';

			foreach ($errors as $value) {
				print '<div class="alert alert-danger" role="alert">';
					echo $value . '<br>';
				print '</div>';
			}

			print '</div></div></div></div>';
		}
	}


	/*
	* Подключение к базе данных
	*/
	public function databaseConnect($connect_string, $dblogin, $dbpassword)
	{
		if (!empty($connect_string)) {
			R::setup( $connect_string, $dblogin, $dbpassword );
		}else{
			// #Подготовка к нормальным переводам!
			// $errors[] = $this->tr('Ошибка подключения к базе данных!', 'Error connecting to database!');
			// return $this->renderError($errors); //переделать
		}
	}


	/*
	* Подключение стандартных модулей
	*/
	public function moduleConnect($modules)
	{
		$modules[] = [
			'install' => 'Install'
		];

		foreach ($modules as $value) {
			$this->findFiles($value, 'modules');
		}
	}


	/*
	* Подключение шаблонов
	*/
	public function templateConnect($template)
	{
		if (!empty($template) && !is_array($template)) {
			$tpl = $_SERVER['DOCUMENT_ROOT'] . '/template/' . $template . '.tpl.php';
			if (file_exists($tpl)) {
				include $tpl;
			}
		}elseif (!empty($template) && is_array($template)){
			foreach ($template as $tplArr) {
				$tpl = $_SERVER['DOCUMENT_ROOT'] . '/template/' . $tplArr . '.tpl.php';
				if (file_exists($tpl)) {
					include $tpl;
				}
			}
		}
	}

	#Создание временного пользователя
	public function guestid()
	{
		$sess = $_SESSION[$this->session_name]->login;
		if (empty($_COOKIE['guestid']) && empty($sess)) {
			setcookie('guestid', substr(md5(rand(0, 50000)), 25));
			$this->redirect('/');
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
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules_custom/' . $name . '/' . $custom . '.module.php') && $custom != '') {
				require $_SERVER['DOCUMENT_ROOT'] . '/modules_custom/' . $name . '/' . $custom . '.module.php';
			}
		}
	}

	/**
	 *  рендер страниц
	 * @str name
	 * @str page
	 * @str file
	 * @str class
	 * @str module
	 * @str url
	 * @str path
	 * @str custom_url
	 * @arr tpl
	 * @arr js
	 * @arr param_cls
	 * @arr module_cst
	 * @str design
	 */
	public function renderPage($page) {

		$pageArr = findPageDB($page);

		returnMethod('User', 'standart', 'checkReferalLink');

		if ($page == 'logout') {
			$this->logout($page);
		}

		if (empty($pageArr)) {
			$this->error404();
		}

		if ($pageArr->name == '') {
	    $this->namePage = $page;
	  }else{
			$this->namePage = $pageArr->name;
		}

	  include 'template/tpl.head.php';

	  $this->addScript([
	    'src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"',
	    'src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"',
	    'src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"'
	  ], true);

	  print '<div class="main-container">
	    <div class="container-content">';

	  if ($pageArr->class) {
	    returnMethod(
				$pageArr->class,
				$pageArr->param_cls,
				$pageArr->method,
				$pageArr->param,
				$pageArr->path,
				$pageArr->module
			);
	  }


	  if ($pageArr->module_cst) {
	    $this->moduleCustom($pageArr->module_cst);
	  }

		if ($pageArr->tpl != 'generate') {
			if (is_array($pageArr->tpl) && $pageArr->design == 'section') {
				foreach ($pageArr->tpl as $tpl) {
					print '<section>';
					$this->templateConnect($tpl);
					print '</section>';
				}
				}elseif ($pageArr->tpl) {
					$this->templateConnect($pageArr->tpl);
				}
		}

	  if ($pageArr->js) {
	    $this->addScript($pageArr->js);
	  }

	  print '</div>';

		if ($page == 'subscribe') //после решения проблемы со стилями - убрать
		{}
		elseif ($page == 'user')
		{}
		else{
			include 'template/tpl.footer.php';
			print '<footer class="footer">
				<div class="commercy_footer">
				<span class="commercy_copyright">
					<div>
						Copyright © ' . date("Y") . '
						vEngine
						|
						(<a href="https://nazhariagames.site/subscribe" class="support_footer">' . $this->tr('Купить', 'Buy') . '</a>)
					</div>
				</span>
				</div>
			</footer>';
		}

	  print '</body>
	  </html>';

	}

	/**
	 * Подключение js скриптов
	 */
	 public function addScript($js = [], $custom = false)
	 {
		 if ($js && !$custom) {
			 foreach ($js as $key) {
				 print '<script src="' . $key . '"></script>';
			 }
		 }else{
			 foreach ($js as $key) {
				 print "<script " . $key . "></script>";
			 }
		 }
	 }

		 public function logout()
		 {
			 if ($this->page == 'logout') {
			 	session_destroy();
			 	$this->redirect('/');
			}elseif (!empty($_POST['logout'])) {
			 	session_destroy();
			 	$this->redirect('/');
			}elseif(!empty($_GET['logout'])) {
				session_destroy();
				$this->redirect('/');
			}
		 }

}
