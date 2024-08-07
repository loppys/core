<?php

namespace Vengine\Packages\Migrations;

use Doctrine\DBAL\Exception;
use Loader\System\Container;
use Vengine\App;
use Vengine\Packages\Migrations\DTO\MigrationResult;
use Vengine\Packages\Migrations\Interfaces\AdapterPHPInterface;
use Vengine\Packages\Migrations\Interfaces\AdapterSQLInterface;
use Vengine\Packages\Migrations\Interfaces\MigrationAdapterInterface;
use Vengine\Packages\Migrations\Interfaces\MigrationManagerInterface;
use Vengine\System\Database\SystemAdapter;
use Vengine\System\Exceptions\AppException;
use Vengine\System\Settings\Structure;
use ReflectionException;

class MigrationManager implements MigrationManagerInterface
{
    public const TABLE = 'migration';

    /**
     * AdapterPHPInterface и AdapterSQLInterface - алиасы для di
     */
    protected MigrationAdapterInterface|AdapterPHPInterface|AdapterSQLInterface $adapter;

    protected SystemAdapter $db;

    protected Structure $structure;

    private bool $checked = false;

    private array $fileList = [];

    private Container $container;

    /**
     * @throws AppException
     */
    public function __construct(
        AdapterPHPInterface $adapter,
        Structure $structure,
        SystemAdapter $db
    ) {
        $this->adapter = $adapter;
        $this->structure = $structure;
        $this->db = $db;

        $app = App::app();

        $this->container = $app->container;
    }

    /**
     * @return void
     *
     * @throws ReflectionException
     */
    public function run(): void
    {
        if (!$this->checked) {
            $this->check();
        }

        $adapterList = [
            AdapterSQLInterface::class,
            AdapterPHPInterface::class
        ];

        foreach ($adapterList as $adapter) {
            $adapter = $this->container->createObject($adapter);

            /** @var MigrationAdapterInterface $adapter */
            $adapter->run($this->fileList);

            $result = $adapter->getResult();

            if (!empty($result)) {
                $this->writeResult($result);
            }
        }
    }

    public function check(): MigrationManagerInterface
    {
        if ($this->checked) {
            return $this;
        }

        if (!$this->db->getConnection()->createSchemaManager()->tableExists(self::TABLE)) {
            // переделать через SchemaManager
            $createTableQuery = <<<SQL
CREATE TABLE IF NOT EXISTS `migration` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `file` LONGTEXT DEFAULT NULL,
    `version` varchar(60) DEFAULT NULL,
    `completed` varchar(1) DEFAULT NULL,
    `fail` LONGTEXT DEFAULT NULL,
    `query` LONGTEXT DEFAULT NULL,
    `date` timestamp NULL DEFAULT current_timestamp(),
    `fullpath` LONGTEXT DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
SQL;

            $this->db->getConnection()->executeQuery($createTableQuery);
        }

        $core = $this->structure->coreMigrations;
        $user = $this->structure->userMigrations;

        $dir = scandir($core);
        unset($dir[0], $dir[1]);
        $this->addFilePath($dir, $core);

        $dir = scandir($user);
        unset($dir[0], $dir[1]);
        $this->addFilePath($dir, $user);

        $this->unsetCompleted();

        $this->checked = true;

        return $this;
    }

    public function setAdapter(MigrationAdapterInterface $adapter): MigrationManagerInterface
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function getAdapter(): MigrationAdapterInterface
    {
        return $this->adapter;
    }

    protected function writeResult(array $resultList): void
    {
        foreach ($resultList as $result) {
            if (!$result instanceof MigrationResult) {
                $this->writeLog(
                    (new MigrationResult())->setError('Невозможно определить результат')
                );

                continue;
            }

            $this->writeLog($result);
        }
    }

    /**
     * @throws Exception
     */
    protected function writeLog(MigrationResult $migrationResult): void
    {
        $table = self::TABLE;

        $this->db->getConnection()->createQueryBuilder()
            ->insert($table)
            ->values([
                'file' => $this->db->escapeValue($migrationResult->getFile()),
                'version' => $this->db->escapeValue($this->getVersion()),
                'query' => $this->db->escapeValue($migrationResult->getDescription()),
                'fail' => $this->db->escapeValue($migrationResult->getError()),
                'fullPath' => $this->db->escapeValue('@deprecated'),
                'completed' => $this->db->escapeValue('Y')
            ])
            ->executeStatement()
        ;
    }

    private function addFilePath(array $dir, string $path): void
    {
        $version = $this->getVersion();

        foreach ($dir as $value) {
            $this->fileList[] = [
                'file' => $value,
                'path' => $path . $value,
                'version' => $version ?: 'undefined'
            ];
        }
    }

    /**
     * @throws Exception
     */
    private function unsetCompleted(): void
    {
        $migrationList = $this->db->getConnection()->createQueryBuilder()
            ->select('*')
            ->from('migration', 'm')
            ->where('m.completed = "Y"')
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($migrationList as $migration) {
            foreach ($this->fileList as $key => $data) {
                if ($data['file'] === $migration['file']) {
                    unset($this->fileList[$key]);
                }
            }
        }
    }

    private function getVersion(): string
    {
        $versionPath = $this->structure->vendor . '/composer/installed.php';

        if (file_exists($versionPath)) {
            $versionInfo = require($versionPath);
        }

        return $versionInfo['versions']['vengine/core']['version'] ?? '';
    }
}
