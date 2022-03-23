<?php

namespace Dev\PerfCode\JiraMigrationReData\Workers;

use Dev\PerfCode\JiraMigrationReData\Models\EpicTable;
use Dev\PerfCode\JiraMigrationReData\Helpers\LogHelper;

class EpicWorker extends Worker
{
    public static function run(): void
    {
        $isRowsEnds = false;
        for ($i = 0; !$isRowsEnds; $i++) {
            $dbResult = EpicTable::getList(
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

                    if (isset($arrResult['FIELDS']['color']['key'])) {
                        $newData['COLOR_KEY'] = $arrResult['FIELDS']['color']['key'];
                    }

                    $newData['FIELDS'] = '';

                    $primaryKey = ['MEMBER_ID' => $arrResult['MEMBER_ID'], 'JIRA_ID' => $arrResult['JIRA_ID']];

                    try {
                        $updateResult = EpicTable::update($primaryKey, $newData);
                        if (!$updateResult->isSuccess()) {
                            $errorMessages = $updateResult->getErrorMessages();
                            LogHelper::updateResultFileLog('EpicErrors', print_r($errorMessages, true), print_r($newData, true), print_r($primaryKey, true));
                        }
                    } catch (\Exception $exception) {
                        $exceptionMessage = $exception->getMessage();
                        LogHelper::updateResultFileLog('EpicExceptions', $exceptionMessage, print_r($newData, true), print_r($primaryKey, true));
                    }
                }
            }
        }
    }
}
