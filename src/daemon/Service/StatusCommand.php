<?php

namespace Flarum\Daemon\Service;

use Flarum\Console\Color;

/**
 * Class statusComand
 * @package Flarum\Daemon\Service
 * @author <trint.dev@gmail.com>
 */
class StatusCommand extends BaseCommand
{

    public function main()
    {
        $pid = $this->getServicePid();
        if (!$pid) {
            (new Color)->println(self::NOT_RUNNING);
            return;
        }
        (new Color)->println(sprintf(self::IS_RUNNING, $pid));
    }

}
