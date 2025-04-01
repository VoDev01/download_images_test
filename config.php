<?php

namespace Config;

enum LogLevels:int
{
    case Info = 1;
    case Extensive = 2;
    case Debug = 3;
}
class ScriptConfig
{
    private static string $saveDir;
    private static int $logLevel = LogLevels::Info->value;
    private static string $logFilePath = "logs.log";

    public static function initialize()
    {
        if(!is_dir($_POST['saveDir']))
        {
            mkdir($_POST['saveDir'], 0775, true);
            self::$saveDir = $_POST['saveDir'];
        }
        else
        {
            self::$saveDir = $_POST['saveDir'];
        }
        self::$logLevel = $_POST['logLevel'];
    }
    public static function getSaveDir()
    {
        return self::$saveDir;
    }
    public static function getLogLevel()
    {
        return self::$logLevel;
    }
    public static function getLogFilePath()
    {
        return self::$logFilePath;
    }
}