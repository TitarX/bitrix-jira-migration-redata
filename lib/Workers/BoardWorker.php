<?php

namespace Dev\PerfCode\JiraMigrationReData\Workers;

use Dev\PerfCode\JiraMigrationReData\Models\BoardTable;
use Dev\PerfCode\JiraMigrationReData\Helpers\LogHelper;

class BoardWorker extends Worker
{
    public static function run(): void
    {
        $isRowsEnds = false;
        for ($i = 0; !$isRowsEnds; $i++) {
            $dbResult = BoardTable::getList(
                [
                    'order' => ['MEMBER_ID' => 'ASC', 'JIRA_ID' => 'ASC'],
                    'filter' => [
                       // 'EXPORTED' => 'N'
                    ],
                    'select' => ['MEMBER_ID', 'JIRA_ID', 'FIELDS'],
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

                    if (isset($arrResult['FIELDS']['location']['projectId'])) {
                        $newData['PROJECT_ID'] = $arrResult['FIELDS']['location']['projectId'];
                    }

                    $newData['FIELDS'] = '';

                    $primaryKey = ['MEMBER_ID' => $arrResult['MEMBER_ID'], 'JIRA_ID' => $arrResult['JIRA_ID']];

                    try {
                        $updateResult = BoardTable::update($primaryKey, $newData);
                        if (!$updateResult->isSuccess()) {
                            $errorMessages = $updateResult->getErrorMessages();
                            LogHelper::updateResultFileLog('BoardErrors', print_r($errorMessages, true), print_r($newData, true), print_r($primaryKey, true));
                        }
                    } catch (\Exception $exception) {
                        $exceptionMessage = $exception->getMessage();
                        LogHelper::updateResultFileLog('BoardExceptions', $exceptionMessage, print_r($newData, true), print_r($primaryKey, true));
                    }
                }
            }
        }
    }
}
