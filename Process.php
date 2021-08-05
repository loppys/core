<?php
/**
 * Ядро!
 */
class Process
{

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
	protected $product_db;

	/*
	* @array
	* @string
	*/
	protected $session_name = 'logged_user';

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
	 protected $namePage;

	/*
	* Конструктор класса
	*/
	function __construct($connect_string, $dblogin, $dbpassword)
	{
		$this->connect_string = $connect_string;
		$this->dblogin = $dblogin;
		$this->dbpassword = $dbpassword;

		$this->sessionStart();

		if (empty($_SESSION[$this->session_name])) {
			$this->guestid();
		}

		if ($connect_string && !R::testConnection()) {
			$this->databaseConnect($this->connect_string, $this->dblogin, $this->dbpassword);
		}
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
		exit(include $_SERVER['DOCUMENT_ROOT'] . '/template/error404.tpl.php');
	}

	/*
	* редиректы!
	*/
	public function redirect($url, $die = 0)
	{
		if ($die == 0) {
			ob_start();
			$redirect = header("Location:" . $url);
			ob_end_flush();
		}

		if ($die == 1) {
			ob_start();
			$redirect = header("Location:" . $url);
			ob_end_flush();
		}

		return $redirect;
	}

	/*
	 * Редирект с задержкой на другие Url (Не этого сайта)
	 * Задел на будущее
	 */
	 public function redirectToAnotherUrl($value='')
	 {
	 	// к 0.4 сделать
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
			$page = 'home';
		}else{
			$page = substr($_SERVER['REQUEST_URI'], 1);

			if ( !preg_match('/^[A-z0-9]{3,60}$/', $page) )
			{
				$this->error404();
			}
		}

		return $page;
	}

	/*
	* Поиск файлов в указанной папке
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

	/*
	* Навигация страниц
	*/
	public function pageNavigation($page)
	{
		return $this->findFiles($page, '_pages');
	}

	/**
	 * Метод генерации кнопок навигации в шапке
	 */
	public function navigationButton()
	{
		##Сначала перенести все настройки в бд, потом писать метод
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
	protected function databaseConnect($connect_string, $dblogin, $dbpassword)
	{
		if (!empty($this->connect_string)) {
			R::setup( $this->connect_string, $this->dblogin, $this->dbpassword );
		}else{
			$errors[] = 'Ошибка подключения к базе данных!';
			#Подготовка к нормальным переводам!
			$errors[] = $this->tr('Ошибка подключения к базе данных!', 'Error connecting to database!');
			return $this->renderError($errors);
		}
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
	public function templateConnect($template)
	{
		if (!empty($template)) {
			$tpl = $_SERVER['DOCUMENT_ROOT'] . '/template/' . $template . '.tpl.php';
			if (file_exists($tpl)) {
				include $tpl;
			}
		}

		// if ($template == 'default') {
		// 	include '/template/';
		// }
	}

	#Создание временного пользователя
	public function guestid()
	{
		$sess = $_SESSION[$this->session_name]->login;
		if (empty($_COOKIE['guestid']) && empty($sess)) {
			setcookie('guestid', substr(md5(rand(0, 50000)), 25));
			$this->redirect('/');
		}
		if (isset($sess)) {
			unset($_COOKIE['guestid']);
		}
	}


	/*
	* Кастомные модули добавлять аккуратно!
	*/
	public function moduleCustom($custom = '')
	{
		if (!empty($custom)) {
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules_custom/' . $custom . '.module.php') && $custom != '') {
				require $_SERVER['DOCUMENT_ROOT'] . '/modules_custom/' . $custom . '.module.php';
			}
		}
	}

	/**
	 *  рендер страниц
	 *	Использовать в версиях >0.4.*
	 *	При версии ниже 0.4 (что маловероятно, но всё же) - использовать templateConnect();
	 */
	public function renderPage(
	$namePage = '',
	$page_tpl = [],
	$js = [],
	$class = [],
	$module_custom = [],
	$section = true
	) {
		if ($namePage != '') {
			$this->namePage = $namePage;
		}

		include 'tpl.head.php';

		$this->addScript([
			'src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"',
			'src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"',
			'src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"'
		], true);

		print '<div class="main-container">
			<div class="container-content">';

		#Если не подходит под стандарт
		#ну или класс сам генерирует страницу
		if ($class) {
			$this->funcUse(
				$class[0],
				$class[1],
				$class[2],
				$class[3],
				$class[4]
			);
		}

		if ($module_custom) {
			$this->moduleCustom($module_custom);
		}

		if ($page_tpl && $section) {
			foreach ($page_tpl as $tpl) {
				print '<section>';
				$this->templateConnect($tpl);
				print '</section>';
			}
		}else{
			foreach ($page_tpl as $tpl) {
				$this->templateConnect($tpl);
			}
		}

		if ($js) {
			$this->addScript($js);
		}

		print '</div>';

		include 'tpl.footer.php';

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
				 print "<script src=" . $key . "></script>";
			 }
		 }else{
			 foreach ($js as $key) {
				 print "<script " . $key . "></script>";
			 }
		 }
	 }

	/**
	 * Костыльный метод вызова функций дочерних классов
	 * Удалить, когда всё будет норм!
	 */
	 public function funcUse($func = '', $class = '', $param = [], $module = false, $core_class = false)
	 {
	 	require $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';

		// $this->findFiles($class, 'modules');

	 	if ( ($func && $class) != '') {
				if ($module) {

					if ($core_class) {
						$tempClass = new $class($this->connect_string, $this->dblogin, $this->dbpassword);

						if ($tempClass && $param) {
							$tempClass->$func(implode(", ", $param));
						}else{
							$tempClass->$func();
						}

					}

					if ($core_class === FALSE) {

						$tempClass = new $class();

							if ($tempClass && $param) {
								$tempClass->$func($param);
							}else{
								$tempClass->$func();
							}
						}
					}
		 		}
		 }

		 public function logout($page)
		 {
			 if ($page == 'logout') {
			 	session_destroy();
			 	$this->redirect('/');
			}elseif (!empty($_POST['logout'])) {
			 	session_destroy();
			 	$this->redirect('/');
			 }
		 }

}
