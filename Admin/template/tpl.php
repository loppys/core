<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;500;700&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="../../core/Admin/styles/app.min.css">
    <link rel="stylesheet" type="text/css" href="/../template/style.css">
    <!-- <link rel="stylesheet" type="text/css" href="https://bootstrap-5.ru/dist/css/bootstrap.min.css"> -->
    <title><?=$this->tr('Админ-панель', 'Admin-panel')?></title>
    <? $this->addScript([
          'src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"',
          'src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"',
          'src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"'
        ], true); ?>
</head>
<!-- content -->
<body>
<!-- <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 pl-3" href="#">$_SESSION[$this->session_name]->login</a>
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="logout">Выход</a>
        </li>
      </ul>
</nav> -->
<!-- navigation -->
<div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="out">
              <sidebar class="side js-side">
                  <div class="side__inner">
                      <!-- header -->
                      <div class="side__header">
                          <a class="side__logo">
                              <img class="side__logo-image" src="../../core/Admin/images/v.jpg">
                              <div class="side__logo-text"><?= $_SESSION[$this->session_name]->login ?></div>
                          </a>
                      </div>
                      <!-- header -->

                      <!-- nav Переделать потом в цикл -->
                      <nav class="nav">
                          <ul class="nav__list">
                              <li class="nav__list-item">
                                  <a href="/admin/home" class="nav__list-link <?=$chapter == 'home' || !$chapter ? 'active' : ''?>" title="Home">
                                      <svg class="nav__list-link-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#19262E" d="M23.121 9.069l-7.585-7.586a5.008 5.008 0 00-7.072 0L.879 9.069A2.978 2.978 0 000 11.19v9.817a3 3 0 003 3h18a3 3 0 003-3V11.19a2.978 2.978 0 00-.879-2.121zM15 22.007H9v-3.934a3 3 0 016 0zm7-1a1 1 0 01-1 1h-4v-3.934a5 5 0 00-10 0v3.934H3a1 1 0 01-1-1V11.19a1.008 1.008 0 01.293-.707L9.878 2.9a3.008 3.008 0 014.244 0l7.585 7.586a1.008 1.008 0 01.293.704z"/></svg>
                                      <span class="nav__list-link-text">Главная</span>
                                  </a>
                              </li>
                              <hr>
                              <br>
                              <li class="nav__list-item">
                                  <a href="/admin/users" class="nav__list-link <?=$chapter == 'users' ? 'active' : ''?>" title="Clients">
                                      <svg class="nav__list-link-icon" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#19262E" d="M7.5 13A4.5 4.5 0 1112 8.5 4.505 4.505 0 017.5 13zm0-7A2.5 2.5 0 1010 8.5 2.5 2.5 0 007.5 6zM15 23v-.5a7.5 7.5 0 00-15 0v.5a1 1 0 002 0v-.5a5.5 5.5 0 0111 0v.5a1 1 0 002 0zm9-5a7 7 0 00-11.667-5.217 1 1 0 101.334 1.49A5 5 0 0122 18a1 1 0 002 0zm-6.5-9A4.5 4.5 0 1122 4.5 4.505 4.505 0 0117.5 9zm0-7A2.5 2.5 0 1020 4.5 2.5 2.5 0 0017.5 2z"/></svg>
                                      <span class="nav__list-link-text">Пользователи</span>
                                  </a>
                              </li>
                              <hr>
                              <br>
                              <li class="nav__list-item">
                                  <a href="/admin/test" class="nav__list-link <?=$chapter == 'test' ? 'active' : ''?>" title="Favorite">
                                      <svg class="nav__list-link-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#19262E" d="M23.836 8.794a3.179 3.179 0 00-3.067-2.226H16.4l-1.327-4.136a3.227 3.227 0 00-6.146 0L7.6 6.568H3.231a3.227 3.227 0 00-1.9 5.832L4.887 15l-1.352 4.187A3.178 3.178 0 004.719 22.8a3.177 3.177 0 003.8-.019L12 20.219l3.482 2.559a3.227 3.227 0 004.983-3.591L19.113 15l3.56-2.6a3.177 3.177 0 001.163-3.606zm-2.343 1.991l-4.144 3.029a1 1 0 00-.362 1.116l1.575 4.87a1.227 1.227 0 01-1.895 1.365l-4.075-3a1 1 0 00-1.184 0l-4.075 3a1.227 1.227 0 01-1.9-1.365l1.58-4.87a1 1 0 00-.362-1.116l-4.144-3.029a1.227 1.227 0 01.724-2.217h5.1a1 1 0 00.952-.694l1.55-4.831a1.227 1.227 0 012.336 0l1.55 4.831a1 1 0 00.952.694h5.1a1.227 1.227 0 01.724 2.217z"/></svg>
                                      <span class="nav__list-link-text">Хз</span>
                                  </a>
                              </li>
                              <hr>
                              <br>
                              <li class="nav__list-item">
                                  <a href="/admin/settigns" class="nav__list-link <?=$chapter == 'settigns' ? 'active' : ''?>" title="Settings">
                                      <svg class="nav__list-link-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#19262E" d="M12 8a4 4 0 104 4 4 4 0 00-4-4zm0 6a2 2 0 112-2 2 2 0 01-2 2z"/><path fill="#19262E" d="M21.294 13.9l-.444-.256a9.1 9.1 0 000-3.29l.444-.256a3 3 0 10-3-5.2l-.445.257A8.977 8.977 0 0015 3.513V3a3 3 0 00-6 0v.513a8.977 8.977 0 00-2.848 1.646L5.705 4.9a3 3 0 00-3 5.2l.444.256a9.1 9.1 0 000 3.29l-.444.256a3 3 0 103 5.2l.445-.257A8.977 8.977 0 009 20.487V21a3 3 0 006 0v-.513a8.977 8.977 0 002.848-1.646l.447.258a3 3 0 003-5.2zm-2.548-3.776a7.048 7.048 0 010 3.75 1 1 0 00.464 1.133l1.084.626a1 1 0 01-1 1.733l-1.086-.628a1 1 0 00-1.215.165 6.984 6.984 0 01-3.243 1.875 1 1 0 00-.751.969V21a1 1 0 01-2 0v-1.252a1 1 0 00-.751-.969A6.984 6.984 0 017.006 16.9a1 1 0 00-1.215-.165l-1.084.627a1 1 0 11-1-1.732l1.084-.626a1 1 0 00.464-1.133 7.048 7.048 0 010-3.75 1 1 0 00-.465-1.129l-1.084-.626a1 1 0 011-1.733l1.086.628A1 1 0 007.006 7.1a6.984 6.984 0 013.243-1.875A1 1 0 0011 4.252V3a1 1 0 012 0v1.252a1 1 0 00.751.969A6.984 6.984 0 0116.994 7.1a1 1 0 001.215.165l1.084-.627a1 1 0 111 1.732l-1.084.626a1 1 0 00-.463 1.129z"/></svg>
                                      <span class="nav__list-link-text">Настройки</span>
                                  </a>
                              </li>
                          </ul>
                      </nav>
                      <!-- nav -->

                      <div class="side__footer">
                          <a class="logout-link" href="/logout" title="Logout">
                              <svg class="logout-link__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#19262E" d="M7 22H5a3 3 0 01-3-3V5a3 3 0 013-3h2a1 1 0 000-2H5a5.006 5.006 0 00-5 5v14a5.006 5.006 0 005 5h2a1 1 0 000-2z"/><path fill="#19262E" d="M18.538 18.707l4.587-4.586a3.007 3.007 0 000-4.242l-4.587-4.586a1 1 0 00-1.414 1.414L21.416 11H6a1 1 0 000 2h15.417l-4.293 4.293a1 1 0 101.414 1.414z"/></svg>
                              <span class="logout-link__text">Выход</span>
                          </a>
                      </div>
                  </div>
              </sidebar>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>

          <h2>Section title</h2>
          <div class="table-responsive">
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Header</th>
                  <th>Header</th>
                  <th>Header</th>
                  <th>Header</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1,001</td>
                  <td>Lorem</td>
                  <td>ipsum</td>
                  <td>dolor</td>
                  <td>sit</td>
                </tr>
                <tr>
                  <td>1,002</td>
                  <td>amet</td>
                  <td>consectetur</td>
                  <td>adipiscing</td>
                  <td>elit</td>
                </tr>
                <tr>
                  <td>1,003</td>
                  <td>Integer</td>
                  <td>nec</td>
                  <td>odio</td>
                  <td>Praesent</td>
                </tr>
                <tr>
                  <td>1,003</td>
                  <td>libero</td>
                  <td>Sed</td>
                  <td>cursus</td>
                  <td>ante</td>
                </tr>
                <tr>
                  <td>1,004</td>
                  <td>dapibus</td>
                  <td>diam</td>
                  <td>Sed</td>
                  <td>nisi</td>
                </tr>
                <tr>
                  <td>1,005</td>
                  <td>Nulla</td>
                  <td>quis</td>
                  <td>sem</td>
                  <td>at</td>
                </tr>
                <tr>
                  <td>1,006</td>
                  <td>nibh</td>
                  <td>elementum</td>
                  <td>imperdiet</td>
                  <td>Duis</td>
                </tr>
                <tr>
                  <td>1,007</td>
                  <td>sagittis</td>
                  <td>ipsum</td>
                  <td>Praesent</td>
                  <td>mauris</td>
                </tr>
                <tr>
                  <td>1,008</td>
                  <td>Fusce</td>
                  <td>nec</td>
                  <td>tellus</td>
                  <td>sed</td>
                </tr>
                <tr>
                  <td>1,009</td>
                  <td>augue</td>
                  <td>semper</td>
                  <td>porta</td>
                  <td>Mauris</td>
                </tr>
                <tr>
                  <td>1,010</td>
                  <td>massa</td>
                  <td>Vestibulum</td>
                  <td>lacinia</td>
                  <td>arcu</td>
                </tr>
                <tr>
                  <td>1,011</td>
                  <td>eget</td>
                  <td>nulla</td>
                  <td>Class</td>
                  <td>aptent</td>
                </tr>
                <tr>
                  <td>1,012</td>
                  <td>taciti</td>
                  <td>sociosqu</td>
                  <td>ad</td>
                  <td>litora</td>
                </tr>
                <tr>
                  <td>1,013</td>
                  <td>torquent</td>
                  <td>per</td>
                  <td>conubia</td>
                  <td>nostra</td>
                </tr>
                <tr>
                  <td>1,014</td>
                  <td>per</td>
                  <td>inceptos</td>
                  <td>himenaeos</td>
                  <td>Curabitur</td>
                </tr>
                <tr>
                  <td>1,015</td>
                  <td>sodales</td>
                  <td>ligula</td>
                  <td>in</td>
                  <td>libero</td>
                </tr>
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>
</body>
