<?php

class vk extends Api
{
    public $apiName = 'vk';

    //Вывод
    public function indexAction()
    {
      var_dump($this->path);
        return $this->response('get', 200);
    }

    //создание
    public function createAction()
    {
      if (!isset($_REQUEST)) {
        return;
      }

      require_once $this->path . 'libs/common/common.php';

      $data = json_decode(file_get_contents('php://input'));

      switch ($data->type) {
        #подтверждение
          case 'confirmation':
          echo $confirmation_code;
          break;
        #посты
        case 'wall_post_new':
          echo('ok');
          break;
        case 'wall_reply_new':
          echo('ok');
          break;
        case 'wall_reply_edit':
          echo('ok');
          break;
        case 'wall_reply_restore':
          echo('ok');
          break;
        case 'wall_reply_delete':
          echo('ok');
          break;
        #фото
        case 'photo_new':
          echo('ok');
          break;
        case 'photo_comment_new':
          // code...
          break;
        case 'photo_comment_restore':
          echo('ok');
          break;
        case 'photo_comment_delete':
          // code...
          break;
        case 'photo_new':
          echo('ok');
          break;

        #Аудио, но ВК шлёт в лес)
        case 'audio_new':
          // тут вообще для галочки! Если потребуется, то будет реализовано
          break;
      }

      return $this->response("post", 200);
    }

    //обновление
    public function updateAction()
    {
        return $this->response("updateAction", 200);
    }

    //удаление
    public function deleteAction()
    {
        return $this->response("deleteAction", 200);
    }
}
