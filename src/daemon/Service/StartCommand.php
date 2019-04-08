<?php

namespace Flarum\Daemon\Service;

use Flarum\Console\Flag;
use Flarum\Console\Color;

/**
 * Class StartComand
 * @package Flarum\Daemon\Service
 * @author <trint.dev@gmail.com>
 */
class StartCommand extends BaseCommand
{

    /**
     * @var bool
     */
    public $update;

    /**
     * @var bool
     */
    public $daemon;

    public function main()
    {
        if ($pid = $this->getServicePid()) {
            (new Color)->println(sprintf(self::IS_RUNNING, $pid));
            return;
        }

        $this->update = Flag::bool(['u', 'update'], false);
        $this->daemon = Flag::bool(['d', 'daemon'], false);

        $server = new \Flarum\Server\Httpd($this->path, $this->setting);

        if ($this->update) {
            $server->setting['max_request'] = 1;
        }
        $server->setting['daemonize'] = $this->daemon;
        $server->start();
    }

}
