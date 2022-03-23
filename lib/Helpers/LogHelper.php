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

    public static function updateResultFileLog(string $logName, string $resultMessage, string $newData, string $identifier = 'Missing', bool $logFileClear = false): void
    {
        $logMessage = ('Date: ' . date('Y.m.d - H:i:s'));
        $logMessage .= PHP_EOL;
        $logMessage .= ('Identifier: ' . $identifier);
        $logMessage .= PHP_EOL;
        $logMessage .= ('New data for update: ' . $newData);
        $logMessage .= PHP_EOL;
        $logMessage .= ('Message: ' . $resultMessage);

        self::fileLog($logName, $logMessage, $logFileClear);
    }
}
