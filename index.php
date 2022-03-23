<?php

if (isset($_SERVER['HTTP_HOST']) || isset($_SERVER['REQUEST_METHOD'])) {
    exit;
}

spl_autoload_register(
    function ($className) {
        $classPath = __DIR__ . '/lib/';

        $className = preg_replace('/^\\\\/', '', $className);
        $className = preg_replace('/^Dev\\\\PerfCode\\\\JiraMigrationReData\\\\/', '', $className);

        $arClassPath = explode('\\', $className);
        $classPath .= implode(DIRECTORY_SEPARATOR, $arClassPath);
        $classPath .= '.php';

        if (file_exists($classPath)) {
            include_once $classPath;
        }
    }
);

use Dev\PerfCode\JiraMigrationReData\Helpers\MiscHelper;
use Dev\PerfCode\JiraMigrationReData\Workers;

$_SERVER['DOCUMENT_ROOT'] = MiscHelper::getSiteDirPath();

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

set_time_limit(0);

/*
 * Поочерёдное выполнение распределения данных полей FIELDS кождой сущности.
 * Выполнение IssueWorker может занять несколько часов, других - несколько секунд.
 */
// Workers\SprintWorker::run();
// Workers\StageWorker::run();
// Workers\EpicWorker::run();
// Workers\CommentWorker::run();
// Workers\BoardWorker::run();
// Workers\IssueWorker::run();

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
