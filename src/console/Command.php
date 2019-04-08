<?php

namespace Flarum\Console;

use Flarum\Console\Flag;
use Flarum\Console\Color;
use Flarum\Console\Arguments;

/**
 * Class Command
 * @package Flarum\Console
 * @author <trint.dev@gmail.com>
 */
class Command
{

    /**
     * @var array
     */
    public $path = [];

    /**
     * @var array
     */
    public $setting = [];

    /**
     * @var array
     */
    public $commands = [];

     /**
     * @var string
     */
    public $commandNamespace = '';


    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->path             = $config['path'];
        $this->setting          = $config['setting'];
        $this->commands         = $config['commands'];
        $this->commandNamespace = $config['commandNamespace'];
    }

    public function run()
    {
        if (!extension_loaded('swoole')) {
            (new Color)->println('Need swoole extension to run, install: https://www.swoole.com/');
            exit;
        }

        if (PHP_SAPI != 'cli') {
            throw new \RuntimeException('Please run in CLI mode.');
        }

        Flag::initialize();
        if (Arguments::subCommand() == '' && Arguments::command() == '') {
            if (Flag::bool(['h', 'help'], false)) {
                $this->help();
                return;
            }
            $options = Flag::options();
            if (empty($options)) {
                $this->help();
                return;
            }
            $keys   = array_keys($options);
            $flag   = array_shift($keys);
            $script = Arguments::script();
            throw new \RuntimeException("flag provided but not defined: '{$flag}', see '{$script} --help'.");
        }
        if ((Arguments::command() !== '' || Arguments::subCommand() !== '') && Flag::bool(['h', 'help'], false)) {
            $this->commandHelp();
            return;
        }
        $command = trim(implode(' ', [Arguments::command(), Arguments::subCommand()]));
        return $this->runAction($command);
    }

    protected function help()
    {
        $script = Arguments::script();
        (new Color)->println("Usage: {$script} [OPTIONS] COMMAND [SUBCOMMAND] [arg...]");
        $this->printOptions();
        $this->printCommands();
        (new Color)->println('');
        (new Color)->println("Run '{$script} COMMAND [SUBCOMMAND] --help' for more information on a command.");
        (new Color)->println('');
    }

    protected function commandHelp()
    {
        $script  = Arguments::script();
        $command = trim(implode(' ', [Arguments::command(), Arguments::subCommand()]));
        (new Color)->println("Usage: {$script} {$command} [arg...]");
        $this->printCommandOptions();
    }

    protected function printOptions()
    {
        (new Color)->println('');
        (new Color)->println('Options:');
        (new Color)->println("  --help\tPrint usage.");
    }

    protected function printCommands()
    {
        (new Color)->println('');
        (new Color)->println('Commands:');
        foreach ($this->commands as $key => $item) {
            $command     = $key;
            $subCommand  = '';
            $description = $item['description'] ?? '';
            if (strpos($key, ' ') !== false) {
                list($command, $subCommand) = explode(' ', $key);
            }
            if ($subCommand == '') {
                (new Color)->println("    {$command}\t{$description}");
            } else {
                (new Color)->println("    {$command} {$subCommand}\t{$description}");
            }
        }
    }

    protected function printCommandOptions()
    {
        $command = trim(implode(' ', [Arguments::command(), Arguments::subCommand()]));
        if (!isset($this->commands[$command]['options'])) {
            return;
        }
        $options = $this->commands[$command]['options'];
        (new Color)->println('');
        (new Color)->println('Options:');
        foreach ($options as $option) {
            $names = array_shift($option);
            if (is_string($names)) {
                $names = [$names];
            }
            $flags = [];
            foreach ($names as $name) {
                if (strlen($name) == 1) {
                    $flags[] = "-{$name}";
                } else {
                    $flags[] = "--{$name}";
                }
            }
            $flag        = implode(', ', $flags);
            $description = $option['description'] ?? '';
            (new Color)->println("  {$flag}\t{$description}");
        }
        (new Color)->println('');
    }

    /**
     * @param $command
     * @return mixed
     */
    public function runAction($command)
    {
        if (!isset($this->commands[$command])) {
            $script = Arguments::script();
            throw new \RuntimeException("'{$command}' is not command, see '{$script} --help'.");
        }

        $shortClass = $this->commands[$command];
        if (is_array($shortClass)) {
            $shortClass = array_shift($shortClass);
        }

        $shortClass    = str_replace('/', "\\", $shortClass);
        $commandDir    = self::dirname($shortClass);
        $commandDir    = $commandDir == '.' ? '' : "$commandDir\\";
        $commandName   = self::basename($shortClass);
        $commandClass  = "{$this->commandNamespace}\\{$commandDir}{$commandName}Command";
        $commandAction = 'main';
        if (!class_exists($commandClass)) {
            throw new \RuntimeException("'{$commandClass}' class not found.");
        }
        $commandInstance = new $commandClass($this->path, $this->setting);
        if (!method_exists($commandInstance, $commandAction)) {
            throw new \RuntimeException("'{$commandClass}::main' method not found.");
        }
        $this->validateOptions($command);
        return call_user_func([$commandInstance, $commandAction]);
    }

    /**
     * @param $command
     */
    protected function validateOptions($command)
    {
        $options  = $this->commands[$command]['options'] ?? [];
        $regflags = [];
        foreach ($options as $option) {
            $names = array_shift($option);
            if (is_string($names)) {
                $names = [$names];
            }
            foreach ($names as $name) {
                if (strlen($name) == 1) {
                    $regflags[] = "-{$name}";
                } else {
                    $regflags[] = "--{$name}";
                }
            }
        }
        foreach (array_keys(Flag::options()) as $flag) {
            if (!in_array($flag, $regflags)) {
                $script      = Arguments::script();
                $command     = Arguments::command();
                $subCommand  = Arguments::subCommand();
                $fullCommand = $command . ($subCommand ? " {$subCommand}" : '');
                throw new \RuntimeException("flag provided but not defined: '{$flag}', see '{$script} {$fullCommand} --help'.");
            }
        }
    }

    /**
     * @param $path
     * @return string
     */
    private static function dirname($path)
    {
        if (strpos($path, '\\') === false) {
            return dirname($path);
        }
        return str_replace('/', '\\', dirname(str_replace('\\', '/', $path)));
    }

    /**
     * @param $path
     * @return string
     */
    private static function basename($path)
    {
        if (strpos($path, '\\') === false) {
            return basename($path);
        }
        return str_replace('/', '\\', basename(str_replace('\\', '/', $path)));
    }

}
