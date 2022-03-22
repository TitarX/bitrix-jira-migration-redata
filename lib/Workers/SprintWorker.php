<?php

namespace Dev\PerfCode\JiraMigrationReData\Workers;

use Bitrix\Main\DB\Exception;
use Dev\PerfCode\JiraMigrationReData\Models\SprintTable;
use Dev\PerfCode\JiraMigrationReData\Helpers\LogHelper;

class SprintWorker extends Worker
{
    public static function run(): void
    {
        $isRowsEnds = false;
        for ($i = 0; !$isRowsEnds; $i++) {
            $dbResult = SprintTable::getList(
                [
                    'order' => ['MEMBER_ID' => 'ASC', 'JIRA_ID' => 'ASC'],
                    'filter' => ['EXPORTED' => 'N'],
                    'select' => ['MEMBER_ID', 'JIRA_ID', 'EXPORTED', 'FIELDS'],
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

                    if (isset($arrResult['FIELDS']['startDate'])) {
                        $newData['START_DATE'] = $arrResult['FIELDS']['startDate'];
                    }

                    if (isset($arrResult['FIELDS']['endDate'])) {
                        $newData['END_DATE'] = $arrResult['FIELDS']['endDate'];
                    }

                    if (isset($arrResult['FIELDS']['state'])) {
                        $newData['STATE'] = $arrResult['FIELDS']['state'];
                    }

                    $newData['FIELDS'] = '';

                    $primaryKey = ['MEMBER_ID' => $arrResult['MEMBER_ID'], 'JIRA_ID' => $arrResult['JIRA_ID']];

                    try {
                        $updateResult = SprintTable::update($primaryKey, $newData);
                        if (!$updateResult->isSuccess()) {
                            $errorMessages = $updateResult->getErrorMessages();

                            $logMessage = 'New data for update: ' . print_r($newData, true);
                            $logMessage .= PHP_EOL;
                            $logMessage .= 'Error messages: ' . print_r($errorMessages, true);

                            LogHelper::writeFileLog('SprintErrors', $logMessage, print_r($primaryKey, true));
                        }
                    } catch (Exception $exception) {
                        $exceptionMessage = $exception->getMessage();

                        $logMessage = 'New data for update: ' . print_r($newData, true);
                        $logMessage .= PHP_EOL;
                        $logMessage .= "Exception messages: {$exceptionMessage}";

                        LogHelper::writeFileLog('SprintExceptions', $logMessage, print_r($primaryKey, true));
                    }
                }
            }
        }
    }
}
