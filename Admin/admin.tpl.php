<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="core/Admin/styles/app.min.css">
    <title> <?= $this->tr('Админ-панель', 'Admin-panel') ?> </title>
    <?  $this->addScript([
    			'src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"',
    			'src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"',
    			'src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"'
    		], true); ?>
</head>
<body>

    <div class="out">
        <sidebar class="side js-side">
            <div class="side__inner">
                <!-- header -->
                <div class="side__header">
                    <a href="#" class="side__logo">
                        <img class="side__logo-image" src="core/Admin/images/v.jpg">
                        <div class="side__logo-text"> <?= $_SESSION[$this->session_name]->login ?> </div>
                    </a>

                    <button class="side-toggle js-side-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect fill="#19262E" y="11" width="24" height="2" rx="1"/><rect fill="#19262E" y="4" width="24" height="2" rx="1"/><rect fill="#19262E" y="18" width="24" height="2" rx="1"/></svg>
                    </button>
                </div>
                <!-- header -->

                <!-- nav -->
                <nav class="nav">
                    <ul class="nav__list">
                        <li class="nav__list-item">
                            <a href="" class="nav__list-link" title="Home">
                                <svg class="nav__list-link-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#19262E" d="M23.121 9.069l-7.585-7.586a5.008 5.008 0 00-7.072 0L.879 9.069A2.978 2.978 0 000 11.19v9.817a3 3 0 003 3h18a3 3 0 003-3V11.19a2.978 2.978 0 00-.879-2.121zM15 22.007H9v-3.934a3 3 0 016 0zm7-1a1 1 0 01-1 1h-4v-3.934a5 5 0 00-10 0v3.934H3a1 1 0 01-1-1V11.19a1.008 1.008 0 01.293-.707L9.878 2.9a3.008 3.008 0 014.244 0l7.585 7.586a1.008 1.008 0 01.293.704z"/></svg>
                                <span class="nav__list-link-text">Home</span>
                            </a>
                        </li>
                        <li class="nav__list-item">
                            <a href="" class="nav__list-link active" title="Clients">
                                <svg class="nav__list-link-icon" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#19262E" d="M7.5 13A4.5 4.5 0 1112 8.5 4.505 4.505 0 017.5 13zm0-7A2.5 2.5 0 1010 8.5 2.5 2.5 0 007.5 6zM15 23v-.5a7.5 7.5 0 00-15 0v.5a1 1 0 002 0v-.5a5.5 5.5 0 0111 0v.5a1 1 0 002 0zm9-5a7 7 0 00-11.667-5.217 1 1 0 101.334 1.49A5 5 0 0122 18a1 1 0 002 0zm-6.5-9A4.5 4.5 0 1122 4.5 4.505 4.505 0 0117.5 9zm0-7A2.5 2.5 0 1020 4.5 2.5 2.5 0 0017.5 2z"/></svg>
                                <span class="nav__list-link-text">Clients</span>
                            </a>
                        </li>
                        <li class="nav__list-item">
                            <a href="" class="nav__list-link" title="Favorite">
                                <svg class="nav__list-link-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#19262E" d="M23.836 8.794a3.179 3.179 0 00-3.067-2.226H16.4l-1.327-4.136a3.227 3.227 0 00-6.146 0L7.6 6.568H3.231a3.227 3.227 0 00-1.9 5.832L4.887 15l-1.352 4.187A3.178 3.178 0 004.719 22.8a3.177 3.177 0 003.8-.019L12 20.219l3.482 2.559a3.227 3.227 0 004.983-3.591L19.113 15l3.56-2.6a3.177 3.177 0 001.163-3.606zm-2.343 1.991l-4.144 3.029a1 1 0 00-.362 1.116l1.575 4.87a1.227 1.227 0 01-1.895 1.365l-4.075-3a1 1 0 00-1.184 0l-4.075 3a1.227 1.227 0 01-1.9-1.365l1.58-4.87a1 1 0 00-.362-1.116l-4.144-3.029a1.227 1.227 0 01.724-2.217h5.1a1 1 0 00.952-.694l1.55-4.831a1.227 1.227 0 012.336 0l1.55 4.831a1 1 0 00.952.694h5.1a1.227 1.227 0 01.724 2.217z"/></svg>
                                <span class="nav__list-link-text">Favorite</span>
                            </a>
                        </li>
                        <li class="nav__list-item">
                            <a href="" class="nav__list-link" title="Settings">
                                <svg class="nav__list-link-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#19262E" d="M12 8a4 4 0 104 4 4 4 0 00-4-4zm0 6a2 2 0 112-2 2 2 0 01-2 2z"/><path fill="#19262E" d="M21.294 13.9l-.444-.256a9.1 9.1 0 000-3.29l.444-.256a3 3 0 10-3-5.2l-.445.257A8.977 8.977 0 0015 3.513V3a3 3 0 00-6 0v.513a8.977 8.977 0 00-2.848 1.646L5.705 4.9a3 3 0 00-3 5.2l.444.256a9.1 9.1 0 000 3.29l-.444.256a3 3 0 103 5.2l.445-.257A8.977 8.977 0 009 20.487V21a3 3 0 006 0v-.513a8.977 8.977 0 002.848-1.646l.447.258a3 3 0 003-5.2zm-2.548-3.776a7.048 7.048 0 010 3.75 1 1 0 00.464 1.133l1.084.626a1 1 0 01-1 1.733l-1.086-.628a1 1 0 00-1.215.165 6.984 6.984 0 01-3.243 1.875 1 1 0 00-.751.969V21a1 1 0 01-2 0v-1.252a1 1 0 00-.751-.969A6.984 6.984 0 017.006 16.9a1 1 0 00-1.215-.165l-1.084.627a1 1 0 11-1-1.732l1.084-.626a1 1 0 00.464-1.133 7.048 7.048 0 010-3.75 1 1 0 00-.465-1.129l-1.084-.626a1 1 0 011-1.733l1.086.628A1 1 0 007.006 7.1a6.984 6.984 0 013.243-1.875A1 1 0 0011 4.252V3a1 1 0 012 0v1.252a1 1 0 00.751.969A6.984 6.984 0 0116.994 7.1a1 1 0 001.215.165l1.084-.627a1 1 0 111 1.732l-1.084.626a1 1 0 00-.463 1.129z"/></svg>
                                <span class="nav__list-link-text">Settings</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- nav -->

                <div class="side__footer">
                    <a class="logout-link" href="logout" title="Logout">
                        <svg class="logout-link__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#19262E" d="M7 22H5a3 3 0 01-3-3V5a3 3 0 013-3h2a1 1 0 000-2H5a5.006 5.006 0 00-5 5v14a5.006 5.006 0 005 5h2a1 1 0 000-2z"/><path fill="#19262E" d="M18.538 18.707l4.587-4.586a3.007 3.007 0 000-4.242l-4.587-4.586a1 1 0 00-1.414 1.414L21.416 11H6a1 1 0 000 2h15.417l-4.293 4.293a1 1 0 101.414 1.414z"/></svg>
                        <span class="logout-link__text">logout</span>
                    </a>
                </div>
            </div>
        </sidebar>

        <main class="main js-main">
            <h1>Client</h1>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Veritatis nostrum porro vel quam deserunt consectetur doloribus dolores iusto, quidem nam ad officiis asperiores. Illum obcaecati natus, ut, nihil nesciunt cumque aperiam ad esse nemo ratione unde dolorem adipisci, sapiente molestiae enim? Similique aut quasi rerum, voluptate praesentium est non sapiente!</p>
        </main>
    </div>

<?= $this->addScript(['src="core/Admin/scripts/app.js"'], true) ?>

</body>
</html>
