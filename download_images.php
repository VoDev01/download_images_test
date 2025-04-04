<?php
require_once './config.php';
require_once './logger.php';
require_once './filesize.php';

use Config\LogLevels;
use Config\ScriptConfig;
use Logging\Logger;
use File\Filesize;

if (!array_key_exists(1, $argv) && !array_key_exists(2, $argv) && !array_key_exists(3, $argv))
{
    echo ("Перед началом работы скрипта введите обязательные параметры в указанном порядке:\n
Название файла в котором указаны ссылки скачивания\n
Папку в которую будут скачиваться файлы\n
Уровень логгирования (1 - информационный, 2 - подробный, 3 - отладочный)\n
Название файла и путь сохранения логов (опциально).\n");
    exit(0);
}

$config = new ScriptConfig($argv);
$images = explode("\n", file_get_contents($argv[1]));
$logger = new Logger($config);
$logger->beginLogging();
$errorLinks = 0;

for ($i = 0; $i < count($images) - 1; $i++)
{
    try
    {
        $img = @file_get_contents(rtrim($images[$i]), false);

        if (!$img)
        {
            $errorLinks++;
            $logger->writeMessage("Файл " . $imgName . " при скачивании вернул ошибку.");
            if ($config->getLogLevel() == LogLevels::Extensive->value)
            {
                $logger->writeMessage("Ссылка " . $images[$i] . " вернула ошибку.");
            }

            if ($config->getLogLevel() == LogLevels::Debug->value)
            {
                $logger->writeMessage("Ссылка " . $images[$i] . " вернула ошибку.");

                $logger->writeMessage("Код ответа сервера: " . explode(' ', $http_response_header[9])[1]);
            }
            continue;
        }

        $imgInfo = pathinfo($images[$i]);
        $imgName = rtrim($imgInfo['basename']);

        if (!file_exists($config->getSaveDir() . "/" . $imgName))
        {
            $success = file_put_contents($config->getSaveDir() . "/" . $imgName, $img);
            if (!$success)
                $logger->writeMessage("Файл " . $imgName . " был пропущен.");
            else
                $logger->writeMessage("Файл " . $imgName . " был успешно скачан.");

            if ($config->getLogLevel() == LogLevels::Extensive->value)
            {
                if (!$success)
                    $logger->writeMessage("Ссылка " . $images[$i] . " была пропущена.");
                else
                    $logger->writeMessage("Ссылка " . $images[$i] . " была скачана.");
            }

            else if ($config->getLogLevel() == LogLevels::Debug->value)
            {
                if (!$success)
                    $logger->writeMessage("Ссылка " . $images[$i] . " была пропущена.");
                else
                    $logger->writeMessage("Ссылка " . $images[$i] . " была скачана.");
                $logger->writeMessage("Код ответа сервера: " . explode(' ', get_headers($images[$i])[9])[1]);

                $logger->writeMessage("Размер файла " . $imgName . ": " . Filesize::getSize($imgName, $config));
            }
        }
        else
        {
            $logger->writeMessage("Файл " . $imgName . " был пропущен так как уже скачан.");
            if ($config->getLogLevel() == LogLevels::Extensive->value)
            {
                $logger->writeMessage("Ссылка " . $images[$i] . " была пропущена так как файл уже скачан.");
            }
            else if ($config->getLogLevel() == LogLevels::Debug->value)
            {
                $logger->writeMessage("Ссылка " . $images[$i] . " была пропущена так как файл уже скачан.");

                $logger->writeMessage("Код ответа сервера: " . explode(' ', $http_response_header[9])[1]);
            }
        }
    }
    catch (Exception $e)
    {
        $logger->writeMessage("При скачивании файла " . $imgName . " произошла ошибка: " . $e->getMessage());
        $errorLinks++;
    }
}
$logger->writeMessage("Количество ссылок, вернувших ошибку: " . $errorLinks);
$logger->endLogging();
