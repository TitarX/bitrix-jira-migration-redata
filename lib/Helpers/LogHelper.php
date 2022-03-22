<?php

namespace Dev\PerfCode\JiraMigrationReData\Helpers;

use Dev\PerfCode\JiraMigrationReData\Helpers\MiscHelper;

class LogHelper
{
    public static function getLogFilePath(string $logName): ?string
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

    public static function writeFileLog(string $logName, string $message, string $identifier = 'Missing', bool $logFileClear = false): void
    {
        $logFilePath = self::getLogFilePath($logName);
        if (isset($logFilePath)) {
            if ($logFileClear && file_exists($logFilePath)) {
                unlink($logFilePath);
            }

            $logMessage = ('Date: ' . date('Y.m.d - H:i:s'));
            $logMessage .= PHP_EOL;
            $logMessage .= ('Identifier: ' . $identifier);
            $logMessage .= PHP_EOL;
            $logMessage .= ('Message: ' . $message);

            file_put_contents($logFilePath, $logMessage, FILE_APPEND);
            file_put_contents($logFilePath, PHP_EOL, FILE_APPEND);
            file_put_contents($logFilePath, PHP_EOL, FILE_APPEND);
            file_put_contents($logFilePath, '----------------------------------------------------------', FILE_APPEND);
            file_put_contents($logFilePath, PHP_EOL, FILE_APPEND);
            file_put_contents($logFilePath, PHP_EOL, FILE_APPEND);
        }
    }
}
