<?php

namespace Vengine\System\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ApiController
{
    protected $data = [];

    protected $status = 200;

    public function __construct()
    {
        $this->data = $this->prepareData();
    }

    public function getJsonResponse(): JsonResponse
    {
        return new JsonResponse($this->data, $this->status);
    }

    public function indexAction(): void
    {
        $jsonData = $this->getJsonResponse();

        $jsonData->send();
    }

    abstract public function prepareData(): array;
}
