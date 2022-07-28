<?php

use Vengine\Modules\Api\Process;

class action extends Process
{
    public $apiName = 'action';

    //Вывод
    public function indexAction()
    {
        return $this->response('indexAction', 200);
    }

    //создание
    public function createAction()
    {
        return $this->response("createAction", 200);
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
