<?php
return [
    'path' => [
        'base'    => __DIR__.'/..',
        'public'  => __DIR__.'/../public',
        'storage' => __DIR__.'/../storage',
    ],
    'setting' => [
        'server'      => [
            'host' => '127.0.0.1',
            'port' => 9501,
        ],
        'enable_coroutine' => true,
        'reactor_num'      => 16,
        'worker_num'       => 16,
        'pid_file'         => '/var/run/flarum-httpd.pid',
        'log_file'         => '/tmp/flarum-httpd.log',
        'reload_async'     => true,
        'max_wait_time'    => 60,
        'open_tcp_nodelay' => true,
        'max_request'      => 0,
    ],
    'commandNamespace' => 'Flarum\Daemon',
    'commands' => [
        'service start' => [
            'Service\Start',
            'description' => 'Start service.',
            'options'     => [
                [['d', 'daemon'], 'description' => "\t" . 'Run in the background'],
                [['u', 'update'], 'description' => "\tEnable code hot update (only sync available"],
            ],
        ],

        'service stop' => [
            'Service\Stop',
            'description' => 'Stop service.',
        ],

        'service restart' => [
            'Service\Restart',
            'description' => 'Restart service.',
            'options'     => [
                [['d', 'daemon'], 'description' => "\t" . 'Run in the background'],
                [['u', 'update'], 'description' => "\tEnable code hot update (only sync available"],
            ],
        ],

        'service reload' => [
            'Service\Reload',
            'description' => 'Reload the worker process of service.',
        ],

        'service status' => [
            'Service\Status',
            'description' => 'Check the status of service.',
        ],
    ],
];
