<?php

namespace Logging;

require_once './config.php';
use Config\ScriptConfig;

class Logger
{
    private $logStream;
    public function __construct()
    {
        $this->logStream = fopen(ScriptConfig::getLogFilePath(), 'w');
    }
    public function beginLogging()
    {
        if (isset($this->logStream))
            fwrite($this->logStream, "[" . date('Y:M:d H:i:s', time()) . ", " . ScriptConfig::getLogLevel() . "] Начало работы скрипта.\n");
    }
    public function writeMessage(string $message)
    {
        if (isset($this->logStream))
            fwrite($this->logStream, "[" . date('Y:M:d H:i:s', time()) . ", " . ScriptConfig::getLogLevel() . "] " . $message . "\n");
    }
    public function endLogging()
    {
        if (isset($this->logStream))
        {
            fwrite($this->logStream, "[" . date('Y:M:d H:i:s', time()) . ", " . ScriptConfig::getLogLevel() . "] Завершение работы скрипта.");
            fclose($this->logStream);
        }
    }
}
