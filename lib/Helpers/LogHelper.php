<?php

namespace Dev\PerfCode\JiraMigrationReData\Helpers;

use Dev\PerfCode\JiraMigrationReData\Helpers\MiscHelper;

class LogHelper
{
    private static function getLogFilePath(string $logName): ?string
    {
        $appDirPath = MiscHelper::getAppDirPath();
        $logDirPath = "{$appDirPath}/log";

        if (!file_exists($logDirPath)) {
            mkdir($logDirPath, 0755, true);
        }

        if (is_dir($logDirPath)) {
            return "{$logDirPath}/{$logName}.txt";
        } else {
            return null;
        }
    }

    private static function fileLog(string $logName, string $logMessage, bool $logFileClear = false): void
    {
        $logFilePath = self::getLogFilePath($logName);
        if (isset($logFilePath)) {
            if ($logFileClear && file_exists($logFilePath)) {
                unlink($logFilePath);
            }

            file_put_contents($logFilePath, $logMessage, FILE_APPEND);
            file_put_contents($logFilePath, PHP_EOL, FILE_APPEND);
            file_put_contents($logFilePath, PHP_EOL, FILE_APPEND);
            file_put_contents($logFilePath, '----------------------------------------------------------', FILE_APPEND);
            file_put_contents($logFilePath, PHP_EOL, FILE_APPEND);
            file_put_contents($logFilePath, PHP_EOL, FILE_APPEND);
        }
    }

    public static function removeLog(string $logName): void
    {
        $logFilePath = self::getLogFilePath($logName);
        if (file_exists($logFilePath)) {
            unlink($logFilePath);
        }
    }

    public static function updateResultFileLog(string $logName, string $resultMessage, string $newData, string $identifier = 'Missing', bool $logFileClear = false): void
    {
        $logMessage = ('Date: ' . date('(e) Y.m.d - H:i:s'));
        $logMessage .= PHP_EOL;
        $logMessage .= ('Identifier: ' . $identifier);
        $logMessage .= PHP_EOL;
        $logMessage .= ('New data for update: ' . $newData);
        $logMessage .= PHP_EOL;
        $logMessage .= ('Message: ' . $resultMessage);

        self::fileLog($logName, $logMessage, $logFileClear);
    }

    public static function emptyFieldDomainLog(string $logName, string $domainName): void
    {
        $logFilePath = self::getLogFilePath($logName);
        $domainName = trim($domainName);

        $isDomainExists = false;
        if (file_exists($logFilePath)) {
            $filePointer = fopen($logFilePath, 'r');
            if ($filePointer) {
                while (($fileString = fgets($filePointer)) !== false) {
                    if (is_string($fileString)) {
                        $fileString = trim($fileString);
                        if ($fileString === $domainName) {
                            $isDomainExists = true;
                            break;
                        }
                    }
                }

                fclose($filePointer);
            }
        }

        if (!$isDomainExists) {
            file_put_contents($logFilePath, $domainName, FILE_APPEND);
            file_put_contents($logFilePath, PHP_EOL, FILE_APPEND);
        }
    }

    public static function workProcessLog(string $logName, string $iDo): void
    {
        $logFilePath = self::getLogFilePath($logName);
        $dateString = date('(e) Y.m.d - H:i:s');
        file_put_contents($logFilePath, "{$dateString} --> {$iDo}", FILE_APPEND);
        file_put_contents($logFilePath, PHP_EOL, FILE_APPEND);
    }
}
