<?php

namespace Flarum\Daemon\Service;

/**
 * Class BaseComand
 * @package Flarum\Daemon\Service
 * @author <trint.dev@gmail.com>
 */
class BaseCommand
{

    const IS_RUNNING   = 'Service is running, PID : %d';
    const NOT_RUNNING  = 'Service is not running.';
    const EXEC_SUCCESS = 'Command executed successfully.';

    /**
     * @var array
     */
    public $setting = [];
    /**
     * @var array
     */
    public $path = [];

    public function __construct($path, $setting)
    {
        $this->path    = $path;
        $this->setting = $setting;
    }

    /**
     * @return bool|string
     */
    public function getServicePid()
    {
        $pidFile = $this->setting['pid_file'] ?? '';
        if (!file_exists($pidFile)) {
            return false;
        }
        $pid = file_get_contents($pidFile);
        if (!is_numeric($pid) || !\Swoole\Process::kill($pid, 0)) {
            return false;
        }
        return $pid;
    }

}
