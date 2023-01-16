<?php

namespace Vengine\System;

use Vengine\Packages\Settings\Storages\ConstStorage;

class Cache
{
    private $cache;
    private $cacheFileName;
    private $cacheFolder;
    private $cacheFullFileName;
    private $cacheFullFilePath;
    private $cacheEnabled;
    private $cacheTime;
    private $dataName;

    public function __construct($dataName)
    {
        if (ConstStorage::CACHE_ENABLED === true) {
            $this->setCacheOn();
        } else {
            $this->setCacheOff();
        }

        $this->setCacheTime(ConstStorage::CACHE_TIME);
        $this->cacheFolder = $_SERVER['DOCUMENT_ROOT'] . ConstStorage::CACHE_FOLDER;

        $this->dataName = $dataName;

        $md5hash = md5($this->dataName);
        $this->cacheFileName = substr($md5hash, 2, 30);
        $this->cacheFullFilePath = $this->cacheFolder . substr($md5hash, 0, 1) . '/' . substr($md5hash, 1, 1) . '/';
        $this->cacheFullFileName = $this->cacheFullFilePath . $this->cacheFileName;
    }

    public function setCacheOn(): void
    {
        $this->cacheEnabled = true;
    }

    public function setCacheOff(): void
    {
        $this->cacheEnabled = false;
    }

    public function getCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    public function setCacheTime(int $seconds): void
    {
        $this->cacheTime = $seconds;
    }

    public function initCacheData(): bool
    {
        if (!$this->cacheEnabled) {
            return false;
        }

        $cacheOld = time() - @filemtime($this->cacheFullFileName);
        if ($cacheOld < $this->cacheTime) {
            $fp = @fopen($this->cacheFullFileName, "r");
            $this->cache = @fread($fp, filesize($this->cacheFullFileName));
            @fclose($fp);

            return true;
        }

        return false;
    }

    public function getCacheData()
    {
        if (!$this->cacheEnabled) {
            return false;
        }

        if (empty($this->cache)) {
            return false;
        } else {
            $fp = @fopen($this->cacheFullFileName, "r");
            $this->cache = @fread($fp, filesize($this->cacheFullFileName));
            @fclose($fp);

            return unserialize($this->cache);
        }
    }

    public function updateCacheData($newData): bool
    {
        if (!$this->cacheEnabled) {
            return false;
        }

        $this->cache = $newData;
        $output = serialize($this->cache);

        if (!@file_exists($this->cacheFullFilePath)) {
            @mkdir($this->cacheFullFilePath, 0777, true);
        }

        $fp = @fopen($this->cacheFullFileName, "w");

        @fwrite($fp, $output);
        @fclose($fp);

        return true;
    }
}
