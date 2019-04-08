<?php

namespace Flarum\Daemon\Service;

use Flarum\Console\Color;

/**
 * Class ReloadComand
 * @package Flarum\Daemon\Service
 * @author <trint.dev@gmail.com>
 */
class ReloadCommand extends BaseCommand
{

    public function main()
    {
        $pid = $this->getServicePid();
        if (!$pid) {
            (new Color)->println(self::NOT_RUNNING);
            return;
        }
        \Swoole\Process::kill($pid, SIGUSR1);
        (new Color)->println(self::EXEC_SUCCESS);
    }

}
