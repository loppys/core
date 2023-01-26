<?php

namespace Vengine\System\Components\Database;

use RedBeanPHP\R;
use Vengine\libs\Helpers\Crypt;
use Vengine\Packages\Updater\Components\Configurator;

class Adapter extends R
{
    /**
     * @var array
     */
    private $param;

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
            $type = $this->param['dbType'];
            $host = $this->param['dbHost'];
            $dbName = $this->param['dbName'];
            $login = $this->param['dbLogin'];
            $password = $this->param['dbPassword'];

            unset($this->param);

            $this->param['connect'] = $type . ':' . 'host=' . $host . ';' . 'dbname=' . $dbName;
            $this->param['login'] = $login;
            $this->param['password'] = $password;
        }
    }

    public function connect(): void
    {
        $param = $this->param;

        if (!self::testConnection()) {
            self::setup($param['connect'], $param['login'], $param['password']);
        }
    }

    /**
     * @param null $table
     * @param array $fields
     *
     * @throws \RedBeanPHP\RedException\SQL
     */
    public function save($table = null, array $fields = []): void
    {
        if ($table && $fields) {
            $db = self::dispense($table);

            foreach ($fields as $keyField => $fieldValue) {
                if ($fieldValue) {
                    $db->$keyField = $fieldValue;
                }
                continue;
            }

            self::store($db);
        }
    }

    public function condition($condition)
    {
        if ($condition) {
            self::exec($condition);
        }
    }
}
