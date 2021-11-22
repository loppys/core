<?php

/**
 *  Создание ссылок в шапке для навигации
 */
class NavigationButton extends Process
{

  /**
   * Метод генерации кнопок навигации в шапке
   * переделать!
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

}
