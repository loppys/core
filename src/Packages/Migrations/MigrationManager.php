<?php

namespace Vengine\Packages\Migrations;

use Vengine\App;
use Vengine\Packages\Migrations\DTO\MigrationResult;
use Vengine\Packages\Migrations\Interfaces\AdapterPHPInterface;
use Vengine\Packages\Migrations\Interfaces\AdapterSQLInterface;
use Vengine\Packages\Migrations\Interfaces\MigrationAdapterInterface;
use Vengine\Packages\Migrations\Interfaces\MigrationManagerInterface;
use Vengine\System\Components\Database\Adapter;
use Vengine\System\Settings\Structure;

class MigrationManager implements MigrationManagerInterface
{
    /**
     * AdapterPHPInterface и AdapterSQLInterface - алиасы для di
     *
     * @var MigrationAdapterInterface|AdapterPHPInterface|AdapterSQLInterface
     */
    protected $adapter;

    /**
     * @var Adapter
     */
    protected $databaseAdapter;

    /**
     * @var Structure
     */
    protected $structure;

    /**
     * @var bool
     */
    private $checked = false;

    /**
     * @var array
     */
    private $fileList = [];

    public function __construct(AdapterPHPInterface $adapter, Structure $structure)
    {
        $this->adapter = $adapter;
        $this->structure = $structure;

        $this->databaseAdapter = App::app()->adapter;
    }

    public function run(): void
    {
        if (!$this->checked) {
            $this->check();
        }

        /** @var MigrationAdapterInterface $sqlAdapter */
        $sqlAdapter = App::app()->createObject(AdapterSQLInterface::class);
        $sqlAdapter->run($this->fileList);

        $sqlResult = $sqlAdapter->getResult();

        if (!empty($sqlResult)) {
            $this->writeResult($sqlResult);
        }

        $this->adapter->run($this->fileList);

        $phpResult = $this->adapter->getResult();

        if (!empty($phpResult)) {
            $this->writeResult($phpResult);
        }
    }

    public function check(): MigrationManagerInterface
    {
        if ($this->checked) {
            return $this;
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

    protected function writeLog(MigrationResult $migrationResult): void
    {
        $table = $this->databaseAdapter::dispense('migration');

        $table->file = $migrationResult->getFile();
        $table->version = $this->getVersion();
        $table->query = $migrationResult->getDescription();
        $table->fail = $migrationResult->getError();
        $table->fullpath = '@deprecated';
        $table->completed = 'Y';

        $this->databaseAdapter::store($table);
    }

    private function addFilePath(array $dir, string $path): void
    {
        $version = $this->getVersion();

        foreach ($dir as $value) {
            $this->fileList[] = [
                'file' => $value,
                'path' => $path . $value,
                'version' => $version ?? 'undefined'
            ];
        }
    }

    private function unsetCompleted(): void
    {
        $migrationList = Adapter::getAll('SELECT * FROM `migration` WHERE `completed` = ?', ['Y']);

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
