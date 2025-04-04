<?php

namespace Logging;

require_once './config.php';
use Config\ScriptConfig;

class Logger
{
    private $logStream;
    private ScriptConfig $config;
    public function __construct(ScriptConfig $config)
    {
        $this->config = $config;
        $this->logStream = fopen($config->getLogFilePath(), 'w');
    }
    public function beginLogging()
    {
        if (isset($this->logStream))
            fwrite($this->logStream, "[" . date('Y:M:d H:i:s', time()) . ", " . $this->config->getLogLevel() . "] Начало работы скрипта.\n");
    }
    public function writeMessage(string $message)
    {
        if (isset($this->logStream))
            fwrite($this->logStream, "[" . date('Y:M:d H:i:s', time()) . ", " . $this->config->getLogLevel() . "] " . $message . "\n");
    }
    public function endLogging()
    {
        if (isset($this->logStream))
        {
            fwrite($this->logStream, "[" . date('Y:M:d H:i:s', time()) . ", " . $this->config->getLogLevel() . "] Завершение работы скрипта.");
            fclose($this->logStream);
        }
    }
}
