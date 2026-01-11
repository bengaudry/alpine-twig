<?php

class Logger
{
    private static ?Logger $instance = null;
    private string $logFile;

    private function __construct()
    {
        $this->logFile = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'
            ? realpath(__DIR__ . "\..") . "\app.log"
            : realpath(__DIR__ . "/..") . "/app.log";

    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Logger();
        }
        return self::$instance;
    }

    public function log(string $message)
    {
        $date = date('Y-m-d H:i:s');
        file_put_contents($this->logFile, "[$date] $message\n", FILE_APPEND);
    }

    private function __clone() {}
    public function __wakeup() {}
}
