<?php

namespace Vengine\Modules\Api;

use Vengine\AbstractModule;

/**
 * @deprecated
 */
abstract class Process extends AbstractModule
{
    public $module = 'Api';
    public $version = '1.0.2';

    public $route = [];

    public $apiName = '';

    protected $method = '';

    public $requestUri = [];
    public $requestParams = [];

    protected $action = '';

    protected $path = '';

    protected $access;

    protected $token;

    public function __construct()
    {
        parent::__construct();

        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        if (empty($this->route)) {
            $this->route = [
                'test' => [
                    'method' => 'GET',
                    'access' => 'ALL'
                ],
            ];
        }

        $this->access = [
            'ALL',
            'USER',
            'ADMIN'
        ];

        $this->requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $this->requestParams = $_REQUEST;

        $apiArr = $this->route[$this->apiName];

        if ($apiArr['token']) {
            $this->token = $apiArr['token'];
        }

        $this->path = $apiArr['path'];

        if ($this->access === 'TOKEN') {
            if ($this->requestParams['token'] !== $this->token) {
                throw new \RuntimeException('wrong token', 401);
            }
        }

        if ($apiArr) {
            if (!in_array($apiArr['access'], $this->access)) { // передлать в нормальную проверку
                throw new \Exception('Invalid access', 401);
            }
        } else {
            throw new \Exception('API Not Found', 404);
        }

        if (!$apiArr['method']) {
            $this->method = $_SERVER['REQUEST_METHOD'];
        } else {
            $this->method = $apiArr['method'];

            if ($this->method !== $_SERVER['REQUEST_METHOD']) {
                throw new \Exception("Invalid method");
            }
        }

        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new \Exception("Unexpected Header");
            }
        }
    }

    public function run()
    {
        $this->action = $this->getAction();

        if (method_exists($this, $this->action)) {
            return $this->{$this->action}();
        } else {
            throw new \Exception('Invalid Method', 405);
        }
    }

    protected function response($data, $status = 500)
    {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
        return json_encode($data);
    }

    private function requestStatus($code)
    {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );

        return $status[$code] ? $status[$code] : $status[500];
    }

    protected function getAction()
    {
        $method = $this->method;

        switch ($method) {
            case 'GET':
                return 'indexAction';
                break;
            case 'POST':
                return 'createAction';
                break;
            case 'PUT':
                return 'updateAction';
                break;
            case 'DELETE':
                return 'deleteAction';
                break;
            default:
                return null;
        }
    }

    abstract protected function indexAction();

    abstract protected function createAction();

    abstract protected function updateAction();

    abstract protected function deleteAction();
}
