<?php
require_once './config.php';
require_once './logger.php';

use Config\LogLevels;
use Config\ScriptConfig;
use Logging\Logger;

ScriptConfig::initialize();

if (is_uploaded_file($_FILES['images']['tmp_name']))
{
    $images = explode("\n", file_get_contents($_FILES['images']['tmp_name']));
    $logger = new Logger;
    $logger->beginLogging();
    $errorLinks = 0;
    for ($i = 0; $i < count($images) - 1; $i++)
    {
        try
        {
            $img = file_get_contents(rtrim($images[$i]));
            $imgInfo = pathinfo($images[$i]);
            $success = file_put_contents(ScriptConfig::getSaveDir() . "/" . rtrim($imgInfo['basename']), $img);
            if (stripos($http_response_header[0], '404') || stripos($http_response_header[0], '500'))
                $errorLinks++;
            if (!$success)
                $logger->writeMessage("Файл " . rtrim($imgInfo['basename']) . " был пропущен.");
            else
                $logger->writeMessage("Файл " . rtrim($imgInfo['basename']) . " был успешно скачан.");
            if (ScriptConfig::getLogLevel() == LogLevels::Extensive->value)
            {
                if (!$success)
                    $logger->writeMessage("Ссылка " . $images[$i] . " была пропущена.");
                else
                    $logger->writeMessage("Ссылка " . $images[$i] . " была скачана.");
            }
            if (ScriptConfig::getLogLevel() == LogLevels::Debug->value)
            {
                if (!$success)
                    $logger->writeMessage("Ссылка " . $images[$i] . " была пропущена.");
                else
                    $logger->writeMessage("Ссылка " . $images[$i] . " была скачана.");
                $logger->writeMessage("Код ответа сервера: " . $http_response_header[0]);
                $fileSizeUnit = "байт";
                $fileSize = stat(ScriptConfig::getSaveDir()."/".rtrim($imgInfo['basename']))['size'];
                $fileSizeConv = 0;
                if ($fileSize / 1024 >= 1)
                {
                    $fileSizeUnit = "кБ";
                    $fileSizeConv = $fileSize / 1024;
                }
                if ($fileSizeConv / 1024 >= 1)
                {
                    $fileSizeUnit = "МБ";
                    $fileSizeConv = $fileSizeConv / 1024;
                }
                if ($fileSizeConv / 1024 >= 1)
                {
                    $fileSizeUnit = "ГБ";
                    $fileSizeConv = $fileSizeConv / 1024;
                }
                $logger->writeMessage("Размер файла " . rtrim($imgInfo['basename']) . ": " . $fileSizeConv . " " . $fileSizeUnit);
            }
        }
        catch (Exception $e)
        {
            $logger->writeMessage("При скачивании файла " . rtrim($imgInfo['basename']) . " произошла ошибка: " . $e->getMessage());
            $errorLinks++;
        }
    }
    $logger->writeMessage("Количество ссылок, вернувших ошибку: " . $errorLinks);
    $logger->endLogging();
}
