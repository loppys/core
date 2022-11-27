<?php

namespace Vengine\libs\Migrations;

use Vengine\System\Components\Database\Adapter;

class Collect
{
    public array $data;

    public $path;

    function __construct($path)
    {
        $this->path = $path;
        $core = $this->path->core . 'Migrations/';
        $user = $this->path->migrations;

        $dir = scandir($core);
        unset($dir[0], $dir[1]);
        $this->set($dir, $core);

        $dir = scandir($user);
        unset($dir[0], $dir[1]);
        $this->set($dir, $user);

        $this->unsetСompleted();
    }

    public function set(array $dir, $path): void
    {
        $info = json_decode(file_get_contents($this->path->project . 'composer.lock'));

        foreach ($info->packages as $key => $value) {
            if ($value->name === 'vengine/core') {
                $info = $info->packages[$key];
                break;
            }
        }

        $version = $info->version;

        foreach ($dir as $value) {
            $this->data[] = [
                'file' => $value,
                'path' => $path . $value,
                'version' => $version
            ];
        }
    }

    public function unsetСompleted()
    {
        $load = Adapter::getAll('SELECT * FROM `migration` WHERE `completed` = ?', ['Y']);
        foreach ($load as $find) {
            foreach ($this->data as $key => $data) {
                if ($data['file'] == $find['file']) {
                    unset($this->data[$key]);
                }
            }
        }
    }
}
