<?php

namespace Vengine\Api\GUID;

use Symfony\Component\HttpFoundation\JsonResponse;
use Vengine\System\Controllers\ApiController;
use Exception;

class GuidController extends ApiController
{
    private string $login;

    public function indexAction($slug = null): JsonResponse
    {
        $this->login = (string)$slug;

        return parent::indexAction($slug);
    }

    /**
     * @return array
     *
     * @throws Exception
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
