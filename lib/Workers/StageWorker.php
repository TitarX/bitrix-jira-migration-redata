<?php

namespace Dev\PerfCode\JiraMigrationReData\Workers;

use Bitrix\Main\DB\Exception;
use Dev\PerfCode\JiraMigrationReData\Models\StageTable;
use Dev\PerfCode\JiraMigrationReData\Helpers\LogHelper;

class StageWorker extends Worker
{
    public static function run(): void
    {
        $isRowsEnds = false;
        for ($i = 0; !$isRowsEnds; $i++) {
            $dbResult = StageTable::getList(
                [
                    'order' => ['MEMBER_ID' => 'ASC', 'JIRA_ID' => 'ASC', 'JIRA_PROJECT_ID' => 'ASC'],
                    'filter' => ['EXPORTED' => 'N'],
                    'select' => ['MEMBER_ID', 'JIRA_ID', 'JIRA_PROJECT_ID', 'EXPORTED', 'FIELDS', 'POSITION'],
                    'limit' => self::DB_SELECT_LIMIT,
                    'offset' => (self::DB_SELECT_LIMIT * $i)
                ]
            );

            $resultCount = $dbResult->getSelectedRowsCount();
            if ($resultCount < self::DB_SELECT_LIMIT) {
                $isRowsEnds = true;
            }

            while ($arrResult = $dbResult->fetch()) {
                if (!empty($arrResult['FIELDS'])) {
                    $newData = [];

                    $lastKey = array_key_last($arrResult['FIELDS']['columnConfig']['columns']);
                    $stageType = 'WORK';
                    if ($arrResult['POSITION'] == 0) {
                        $stageType = 'START';
                    } elseif ($arrResult['POSITION'] == $lastKey) {
                        $stageType = 'FINISH';
                    }

                    $newData['STAGE_TYPE'] = $stageType;
                    $newData['FIELDS'] = '';

                    $primaryKey = ['MEMBER_ID' => $arrResult['MEMBER_ID'], 'JIRA_ID' => $arrResult['JIRA_ID'], 'JIRA_PROJECT_ID' => $arrResult['JIRA_PROJECT_ID']];

                    try {
                        $updateResult = StageTable::update($primaryKey, $newData);
                        if (!$updateResult->isSuccess()) {
                            $errorMessages = $updateResult->getErrorMessages();
                            LogHelper::updateResultFileLog('StageErrors', print_r($errorMessages, true), print_r($newData, true), print_r($primaryKey, true));
                        }
                    } catch (Exception $exception) {
                        $exceptionMessage = $exception->getMessage();
                        LogHelper::updateResultFileLog('StageExceptions', $exceptionMessage, print_r($newData, true), print_r($primaryKey, true));
                    }
                }
            }
        }
    }
}
