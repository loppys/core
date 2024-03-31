<?php

namespace Vengine\System\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Vengine\libs\Helpers\Crypt;
use Vengine\Packages\Updater\Components\Configurator;

class SystemAdapter
{
    private array $param;

    private Connection $connection;

    public function __construct(Configurator $configurator)
    {
        $config = $configurator->getConfig();
        $database = $config['database'];

        if ($config['app']['crypt'] === true) {
            $this->param = array_map(static function ($item) {
                return Crypt::dsDecrypt($item);
            }, $database);
        } else {
            $this->param = $database;
        }

        if (!empty($this->param)) {
            $this->connect();
        }
    }

    private function connect(): void
    {
        $type = $this->param['dbType'];
        $host = $this->param['dbHost'];
        $dbName = $this->param['dbName'];
        $login = $this->param['dbLogin'];
        $password = $this->param['dbPassword'];

        $this->connection = DriverManager::getConnection(
            [
                'dbname' => $dbName,
                'user' => $login,
                'password' => $password,
                'host' => $host,
                'driver' => $type,
            ]
        );
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function escapeValue(mixed $value, bool $column = false): int|string|bool
    {
        if (is_array($value)) {
            return '';
        }

        switch (gettype($value)) {
            case 'integer':
                if ($column) {
                    return '';
                }

                return (int)$value;
            case 'boolean':
                if ($column) {
                    return '';
                }

                return (bool)$value;
            default:
                if ($column) {
                    return '`' . addslashes($value) . '`';
                }

                return '"' . addslashes($value) . '"';
        }
    }
}