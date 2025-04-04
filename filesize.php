<?php
namespace File;

require_once './config.php';

use Config\ScriptConfig;

class Filesize
{
    public static function getSize(string $fileName, ScriptConfig $config)
    {
        $fileSizeUnit = "байт";
        $fileSize = filesize($config->getSaveDir() . "/" . rtrim($fileName));
        $fileSizeConv = 0;
        if ($fileSize / 1024 >= 1)
        {
            $fileSizeUnit = "кБ";
            $fileSizeConv = round($fileSize / 1024, 1);
        }
        if ($fileSizeConv / 1024 >= 1)
        {
            $fileSizeUnit = "МБ";
            $fileSizeConv = round($fileSizeConv / 1024, 1);
        }
        if ($fileSizeConv / 1024 >= 1)
        {
            $fileSizeUnit = "ГБ";
            $fileSizeConv = round($fileSizeConv / 1024, 1);
        }
        return $fileSizeConv . " " . $fileSizeUnit;
    }
}