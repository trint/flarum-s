<?php

namespace Flarum\Server;

/**
 * Class SwooleEvent
 * @package Flarum\Server
 * @author <trint.dev@gmail.com>
 */
class SwooleEvent
{
    const START         = 'start';
    const MANAGER_START = 'managerStart';
    const WORKER_START  = 'workerStart';
    const REQUEST       = 'request';
}
