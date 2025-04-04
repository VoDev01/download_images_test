<?php

namespace Config;

enum LogLevels: int
{
    case Info = 1;
    case Extensive = 2;
    case Debug = 3;
}
class ScriptConfig
{
    private string $saveDir = 'images';
    private int $logLevel = LogLevels::Info->value;
    private string $logFilePath = "logs.log";

    public function __construct(array $argv)
    {
        if (!is_dir($argv[2]))
        {
            mkdir($argv[2], 0775, true);
            $this->saveDir = $argv[2];
        }
        else
        {
            $this->saveDir = $argv[2];
        }
        $this->logLevel = $argv[3];
        if (array_key_exists(4, $argv))
        {
            $log = fopen($argv[4], 'r');
            if ($log)
            {
                $this->logFilePath = $argv[4];
                fclose($log);
            }
            else
            {
                $log = fopen($this->logFilePath, 'r');
                fclose($log);
            }
        }
        else
        {
            $log = fopen($this->logFilePath, 'r');
            fclose($log);
        }
    }
    public function getSaveDir()
    {
        return $this->saveDir;
    }
    public function getLogLevel()
    {
        return $this->logLevel;
    }
    public function getLogFilePath()
    {
        return $this->logFilePath;
    }
}
