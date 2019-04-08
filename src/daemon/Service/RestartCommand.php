<?php

namespace Flarum\Daemon\Service;

use Flarum\Console\Color;
use Swoole\Process;

/**
 * Class RestartComand
 * @package Flarum\Daemon\Service
 * @author <trint.dev@gmail.com>
 */
class RestartCommand extends StartCommand
{

    public function main()
    {
        $pid = $this->getServicePid();
        if (!$pid) {
            (new Color)->println(self::NOT_RUNNING);
            return;
        }

        Process::kill($pid);
        while (Process::kill($pid, 0)) {
            usleep(100000);
        }
        parent::main();
    }

}
