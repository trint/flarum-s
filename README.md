<p align="center"><img src="https://flarum.org/img/logo.png"></p>

<p align="center">
<a href="https://travis-ci.org/flarum/core"><img src="https://travis-ci.org/flarum/core.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/flarum/core"><img src="https://poser.pugx.org/flarum/core/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/flarum/core"><img src="https://poser.pugx.org/flarum/core/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/flarum/core"><img src="https://poser.pugx.org/flarum/core/license.svg" alt="License"></a>
</p>

## About Flarum

**[Flarum](https://flarum.org/) is a delightfully simple discussion platform for your website.** It's fast and easy to use, with all the features you need to run a successful community. It is designed to be:

* **Fast and simple.** No clutter, no bloat, no complex dependencies. Flarum is built with PHP so it’s quick and easy to deploy. The interface is powered by Mithril, a performant JavaScript framework with a tiny footprint.

* **Beautiful and responsive.** This is forum software for humans. Flarum is carefully designed to be consistent and intuitive across platforms, out-of-the-box.

* **Powerful and extensible.** Customize, extend, and integrate Flarum to suit your community. Flarum’s architecture is amazingly flexible, with a powerful Extension API.

![screenshot](https://flarum.org/img/screenshot.png)

## Requirements

| Dependency | Requirement |
| -------- | -------- |
| [PHP](https://secure.php.net/manual/en/install.php) | `>= 7.2` `Recommend PHP7+` |
| [Swoole](https://www.swoole.co.uk/) | `>= 2.0.12` `No longer support PHP5 since 2.0.12` `Recommend 4.3+` |

## Installation (Flarum + Swoole)

You must have SSH access to a server with **PHP 7.2+** and **MySQL 5.6+**, and install [Composer](https://getcomposer.org/).

Install Swoole
```bash
$ pecl install swoole
```

Install Project Flarum-S 
```
$ composer create-project flarum/flarum flarum-s --stability=beta
```

Install Http Server Swoole for Flarum-S
```bash
$ composer require trint/flarum-s:dev-master
$ composer update -o
$ cp -rf vendor/trint/flarum-s/bin/ bin/
$ chmod 755 bin/flarum-s
```

Config ip, port
```
Change `bin/config.php`: listen_ip, listen_port
```

Commands Service

| Command | Description |
| --------- | --------- |
| `start` | Start Http Server Flarum-S  |
| `stop` | stop |
| `restart` | restart |
| `reload` | reload |


## Usage

```bash
$ cd bin/
$ ./flarum-s service start
```


```
        ___________.__
        \_   _____/|  | _____ _______ __ __  _____
         |    __)  |  | \__  \_  __ \  |  \/     \
         |     \   |  |__/ __ \|  | \/  |  /  Y Y  \
         \___  /   |____(____  /__|  |____/|__|_|  /
             \/              \/                  \/

Server         Name:      flarum-httpd
System         Name:      linux
PHP            Version:   7.3.3-1+ubuntu18.04.1+deb.sury.org+1
Swoole         Version:   4.3.1
Coroutine      Mode:      enabled
Listen         Addr:      127.0.0.1
Listen         Port:      9501
Reactor        Num:       4
Worker         Num:       4
```

Open your browser to http://127.0.0.1:9051 to validate you're up and running.



## Benchmark

using 4 CPUs / 1 GB Memory / PHP 7.3 / Ubuntu 18.04.4 x64

Benchmarking Tool: [wrk](https://github.com/wg/wrk)

```
wrk -t4 -c100 http://your.app
```

### PHP7.3

```
$ cd public/
$ php -S 127.0.0.1:8080
$ wrk -t2 -c50 -d5s http://localhost:8080

Running 5s test @ http://localhost:8080
  2 threads and 50 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency   377.47ms   46.35ms 462.19ms   93.74%
    Req/Sec    64.16     12.73   100.00     60.61%
  639 requests in 5.02s, 8.28MB read
  Socket errors: connect 0, read 639, write 0, timeout 0
Requests/sec:    127.25
Transfer/sec:      1.65MB
```

### Swoole HTTP Server

```
wrk -t4 -c10 http://lumen-swoole.local:1215

Running 10s test @ http://lumen-swoole.local:1215
  4 threads and 10 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency     2.39ms    4.88ms 105.21ms   94.55%
    Req/Sec     1.26k   197.13     1.85k    68.75%
  50248 requests in 10.02s, 10.88MB read
Requests/sec:   5016.94
Transfer/sec:      1.09MB
```

## License

Flarum is open-source software licensed under the [MIT License](https://github.com/flarum/flarum/blob/master/LICENSE).

