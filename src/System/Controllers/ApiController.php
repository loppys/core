<?php

namespace Vengine\System\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Vengine\App;

abstract class ApiController
{
    protected array $data = [];

    protected int $status = 200;

    protected array $errorList = [];

    protected Request $request;

    public function __construct()
    {
        $this->request = App::getRequest();
    }

    public function indexAction($slug = null): JsonResponse
    {
        $this->setData($this->prepareData());

        return $this->getJsonResponse()->prepare($this->request)->send();
    }

    protected function getJsonResponse(): JsonResponse
    {
        if (!empty($this->errorList)) {
            $this->setStatus(400);

            $data = [
                'errors' => $this->errorList
            ];
        } else {
            $data = [
                'response' => $this->data
            ];
        }

        return new JsonResponse($data, $this->status);
    }

    protected function setData(array $data): void
    {
        $this->data = $data;
    }

    protected function setStatus(int $status): void
    {
        $this->status = $status;
    }

    protected function addError(string $code, string $text = ''): void
    {
        $this->errorList[] = [
            'code' => $code,
            'text' => $text,
        ];
    }

    protected function getErrorList(): array
    {
        return $this->errorList;
    }

    abstract public function prepareData(): array;
}
