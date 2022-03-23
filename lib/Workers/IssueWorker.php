<?php

namespace Dev\PerfCode\JiraMigrationReData\Workers;

use Dev\PerfCode\JiraMigrationReData\Models\IssueTable;
use Dev\PerfCode\JiraMigrationReData\Helpers\LogHelper;

class IssueWorker extends Worker
{
    public static function run(): void
    {
        $isRowsEnds = false;
        for ($i = 0; !$isRowsEnds; $i++) {
            $dbResult = IssueTable::getList(
                [
                    'order' => ['MEMBER_ID' => 'ASC', 'JIRA_ID' => 'ASC'],
                    'filter' => [
                        // 'LOGIC' => 'OR',
                        // 'EXPORTED' => 'N',
                        // 'EXPORTED_SUBTASK' => 'N'
                    ],
                    'select' => ['MEMBER_ID', 'JIRA_ID', 'EXPORTED', 'EXPORTED_SUBTASK', 'FIELDS'],
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
                    $comments = [];
                    foreach ($arrResult['FIELDS']['fields']['comment']['comments'] as $issueCommentKey => $issueComment) {
                        $comments[$issueCommentKey] = [
                            'created' => $issueComment['created'],
                            'authorAccountId' => $issueComment['author']['accountId'],
                            'body' => $issueComment['body']
                        ];
                    }

                    $fields = [
                        'created' => $arrResult['FIELDS']['fields']['created'],
                        'updated' => $arrResult['FIELDS']['fields']['updated'],
                        'labels' => $arrResult['FIELDS']['fields']['labels'],
                        'summary' => $arrResult['FIELDS']['fields']['summary'],
                        'duedate' => $arrResult['FIELDS']['fields']['duedate'],
                        'statusId' => $arrResult['FIELDS']['fields']['status']['id']
                    ];

                    $fields['projectId'] = '';
                    if (!empty($arrResult['FIELDS']['fields']['project']['id'])) {
                        $fields['projectId'] = $arrResult['FIELDS']['fields']['project']['id'];
                    }
                    $fields['epicId'] = '';
                    if (!empty($arrResult['FIELDS']['fields']['epic']['id'])) {
                        $fields['epicId'] = $arrResult['FIELDS']['fields']['epic']['id'];
                    }
                    $fields['sprintId'] = '';
                    if (!empty($arrResult['FIELDS']['fields']['sprint']['id'])) {
                        $fields['sprintId'] = $arrResult['FIELDS']['fields']['sprint']['id'];
                    }
                    $fields['creatorAccountId'] = '';
                    if (!empty($arrResult['FIELDS']['fields']['creator']['accountId'])) {
                        $fields['creatorAccountId'] = $arrResult['FIELDS']['fields']['creator']['accountId'];
                    }
                    $fields['assigneeAccountId'] = '';
                    if (!empty($arrResult['FIELDS']['fields']['assignee']['accountId'])) {
                        $fields['assigneeAccountId'] = $arrResult['FIELDS']['fields']['assignee']['accountId'];
                    }
                    $fields['reporterAccountId'] = '';
                    if (!empty($arrResult['FIELDS']['fields']['reporter']['accountId'])) {
                        $fields['reporterAccountId'] = $arrResult['FIELDS']['fields']['reporter']['accountId'];
                    }
                    $fields['description'] = '';
                    if (!empty($arrResult['FIELDS']['fields']['issuetype']['description'])) {
                        $fields['description'] = $arrResult['FIELDS']['fields']['issuetype']['description'];
                    }

                    $fields['subtasks'] = '';
                    if (isset($arrResult['FIELDS']['fields']['subtasks']) && is_array($arrResult['FIELDS']['fields']['subtasks'])) {
                        $fields['subtasks'] = array();
                        foreach ($arrResult['FIELDS']['fields']['subtasks'] as $subtaskKey => $subtaskValue) {
                            $fields['subtasks'][$subtaskKey]['id'] = $subtaskValue['id'];
                        }
                    }

                    $newData = [
                        'FIELDS' => $fields,
                        'COMMENTS' => $comments
                    ];

                    $primaryKey = ['MEMBER_ID' => $arrResult['MEMBER_ID'], 'JIRA_ID' => $arrResult['JIRA_ID']];

                    try {
                        $updateResult = IssueTable::update($primaryKey, $newData);
                        if (!$updateResult->isSuccess()) {
                            $errorMessages = $updateResult->getErrorMessages();
                            LogHelper::updateResultFileLog('IssueErrors', print_r($errorMessages, true), print_r($newData, true), print_r($primaryKey, true));
                        }
                    } catch (\Exception $exception) {
                        $exceptionMessage = $exception->getMessage();
                        LogHelper::updateResultFileLog('IssueExceptions', $exceptionMessage, print_r($newData, true), print_r($primaryKey, true));
                    }
                }
            }
        }
    }
}
