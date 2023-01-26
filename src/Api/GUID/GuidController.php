<?php

namespace Vengine\Api\GUID;

use Symfony\Component\HttpFoundation\JsonResponse;
use Vengine\System\Controllers\ApiController;

class GuidController extends ApiController
{
    /**
     * @var string
     */
    private $login;

    public function indexAction($login = null): JsonResponse
    {
        $this->login = (string)$login;

        return parent::indexAction($login);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function prepareData(): array
    {
        if (!empty($this->login)) {
            return [
                'guid' => getGUID($this->login)
            ];
        }

        $this->addError('login_empty', 'Логин не указан');

        return [];
    }
}
