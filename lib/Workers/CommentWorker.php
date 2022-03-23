<?php

namespace Dev\PerfCode\JiraMigrationReData\Workers;

use Dev\PerfCode\JiraMigrationReData\Models\CommentTable;
use Dev\PerfCode\JiraMigrationReData\Helpers\LogHelper;

class CommentWorker extends Worker
{
    public static function run(): void
    {
        $isRowsEnds = false;
        for ($i = 0; !$isRowsEnds; $i++) {
            $dbResult = CommentTable::getList(
                [
                    'order' => ['MEMBER_ID' => 'ASC', 'JIRA_ID' => 'ASC', 'JIRA_ISSUE_ID' => 'ASC'],
                    'filter' => ['EXPORTED' => 'N'],
                    'select' => ['MEMBER_ID', 'JIRA_ID', 'JIRA_ISSUE_ID', 'EXPORTED', 'FIELDS'],
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

                    if (isset($arrResult['FIELDS']['POST_MESSAGE'])) {
                        $newData['POST_MESSAGE'] = $arrResult['FIELDS']['POST_MESSAGE'];
                    }

                    if (isset($arrResult['FIELDS']['POST_DATE'])) {
                        $newData['POST_DATE'] = $arrResult['FIELDS']['POST_DATE'];
                    }

                    if (isset($arrResult['FIELDS']['AUTHOR_ID'])) {
                        $newData['AUTHOR_ID'] = $arrResult['FIELDS']['AUTHOR_ID'];
                    }

                    $newData['FIELDS'] = '';

                    $primaryKey = ['MEMBER_ID' => $arrResult['MEMBER_ID'], 'JIRA_ID' => $arrResult['JIRA_ID'], 'JIRA_ISSUE_ID' => $arrResult['JIRA_ISSUE_ID']];

                    try {
                        $updateResult = CommentTable::update($primaryKey, $newData);
                        if (!$updateResult->isSuccess()) {
                            $errorMessages = $updateResult->getErrorMessages();
                            LogHelper::updateResultFileLog('CommentErrors', print_r($errorMessages, true), print_r($newData, true), print_r($primaryKey, true));
                        }
                    } catch (\Exception $exception) {
                        $exceptionMessage = $exception->getMessage();
                        LogHelper::updateResultFileLog('CommentExceptions', $exceptionMessage, print_r($newData, true), print_r($primaryKey, true));
                    }
                }
            }
        }
    }
}
