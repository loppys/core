<?php

namespace Vengine\System\Components\Database;

use RedBeanPHP\R;
use Vengine\libs\Helpers\Crypt;
use Vengine\Packages\Updater\Components\Configurator;
use RedBeanPHP\RedException\SQL as SQLException;
use RedBeanPHP\Cursor;
use RedBeanPHP\OODBBean;

/**
 * @deprecated
 */
class Adapter extends R
{
    private array $param;

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
     * @throws SQLException
     */
    public function save($table = null, array $fields = []): void
    {
        if ($table && $fields) {
            $db = self::dispense($table);

            foreach ($fields as $keyField => $fieldValue) {
                if ($fieldValue) {
                    $db->$keyField = $fieldValue;
                }
            }

            self::store($db);
        }
    }

    public function condition($condition): int|array|null|Cursor
    {
        return self::exec($condition);
    }

    public function hardInsert(string $table, array $data): bool
    {
        if (empty($table) || empty($data)) {
            return false;
        }


        return (bool)$this->condition($this->getInsertQuery($table, $data));
    }

    public function insert(string $table, array $data): bool
    {
        if (empty($table) || empty($data)) {
            return false;
        }

        $tableObj = $this->getTable($table);

        if (!is_object($tableObj)) {
            return false;
        }

        foreach ($data as $column => $value) {
            if (empty($value)) {
                continue;
            }

            $tableObj->{$column} = $value;
        }

        self::store($tableObj);

        return true;
    }

    public function getInsertQuery(string $table, array $data): string
    {
        if (empty($table) || empty($data)) {
            return '';
        }

        $dataList = [];
        $columnList = [];
        foreach ($data as $column => $insertData) {
            if (is_string($column)) {
                $columnList[] = $column;
            }

            $dataList[] = $insertData;
        }

        $columnString = $this->columnEscape($columnList);
        $dataString = $this->arrayEscape($dataList);

        return <<<SQL
INSERT INTO `{$table}` ({$columnString}) VALUES ({$dataString})
SQL;
    }

    public function columnEscape(array $columns): string
    {
        return $this->arrayEscape($columns, true);
    }

    public function arrayEscape(array $data = [], bool $column = false): string
    {
        if (count($data) === 1) {
            return $this->escapeValue($data[0], $column);
        }

        $firstKey = array_key_first($data);

        $str = '';
        foreach ($data as $key => $item) {
            $tmp = $this->escapeValue($item, $column);

            if (empty($tmp) && !is_int($tmp)) {
                continue;
            }

            if ($key === $firstKey) {
                $str = $tmp;

                continue;
            }

            $str .= ',' . $tmp;
        }

        return $str;
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

    public function getTable(string $tableName): array|OODBBean
    {
        return static::dispense($tableName);
    }
}
