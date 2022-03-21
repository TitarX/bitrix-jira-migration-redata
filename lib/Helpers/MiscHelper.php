<?php

namespace Dev\PerfCode\JiraMigrationReData\Helpers;

class MiscHelper
{
    public static function getSiteDirPath(): string
    {
        return realpath(__DIR__ . '/../../..');
    }

    public static function getAppDirPath(): string
    {
        return realpath(__DIR__ . '/../..');
    }

    public static function getAppDirRelativePath(): string
    {
        $result = str_replace(self::getSiteDirPath(), '', __DIR__);
        $result = str_replace('/lib', '', $result);
        return $result;
    }
}
