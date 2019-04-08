<?php

namespace Flarum\Console;

/**
 * Class Flag
 * @package Flarum\Console
 * @author <trint.dev@gmail.com>
 */
class Flag
{

    /**
     * @var array
     */
    protected static $_options = [];

    public static function initialize()
    {
        $start = 2;
        if (Arguments::subCommand() == '') {
            $start = 1;
        }
        if (Arguments::command() == '') {
            $start = 0;
        }
        $argv = $GLOBALS['argv'];
        $tmp  = [];
        foreach ($argv as $key => $item) {
            if ($key <= $start) {
                continue;
            }
            $name  = $item;
            $value = '';
            if (strpos($name, '=') !== false) {
                list($name, $value) = explode('=', $item);
            }
            if (substr($name, 0, 2) == '--' || substr($name, 0, 1) == '-') {
                if (substr($name, 0, 1) == '-' && $value === '' && isset($argv[$key + 1])) {
                    $next = $argv[$key + 1];
                    if (preg_match('/^[\S\s]+$/i', $next)) {
                        $value = $next;
                    }
                }
            } else {
                $name = '';
            }
            if ($name !== '') {
                $tmp[$name] = $value;
            }
        }
        self::$_options = $tmp;
    }

    /**
     * @param $name
     * @param bool $default
     * @return bool
     */
    public static function bool($name, $default = false)
    {
        foreach (self::$_options as $key => $value) {
            $names = [$name];
            if (is_array($name)) {
                $names = $name;
            }
            foreach ($names as $item) {
                if (strlen($item) == 1) {
                    $names[] = "-{$item}";
                } else {
                    $names[] = "--{$item}";
                }
            }
            if (in_array($key, $names)) {
                if ($value === 'false') {
                    return false;
                }
                return true;
            }
        }
        return $default;
    }

    /**
     * @param string|array $name
     * @param string $default
     * @return string
     */
    public static function string($name, $default = '')
    {
        foreach (self::$_options as $key => $value) {
            $names = [$name];
            if (is_array($name)) {
                $names = $name;
            }
            foreach ($names as $item) {
                if (strlen($item) == 1) {
                    $names[] = "-{$item}";
                } else {
                    $names[] = "--{$item}";
                }
            }
            if (in_array($key, $names)) {
                if ($value === '') {
                    return $default;
                }
                return $value;
            }
        }
        return $default;
    }

    /**
     * @return array
     */
    public static function options()
    {
        return self::$_options;
    }

}
