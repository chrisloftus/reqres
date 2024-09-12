<?php

declare(strict_types=1);

namespace ChrisLoftus\Plentific;

use Monolog\Level;
use Monolog\Handler\StreamHandler;

class Logger extends \Monolog\Logger
{
    private static $log = null;

    public function __construct()
    {
        parent::__construct('log');

        $path = __DIR__ . '/../log.log';

        $this->pushHandler(new StreamHandler($path, Level::Debug));
    }

    public static function getInstance()
    {
        if (empty(self::$log)) {
            self::$log = new Logger('log');
        }

        return self::$log;
    }
}
