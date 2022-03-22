<?php

namespace Dev\PerfCode\JiraMigrationReData\Workers;

use Dev\PerfCode\JiraMigrationReData\Models\IssueTable;

class IssueWorker extends Worker
{
    public static function run(): void
    {
        $isRowsEnds = false;
        for ($i = 0; !$isRowsEnds; $i++) {
            $dbResult = IssueTable::getList(
                [
                    'order' => ['DOMAIN' => 'asc'],
                    'filter' => [
                        'LOGIC' => 'OR',
                        'EXPORTED' => 'N',
                        'EXPORTED_SUBTASK' => 'N'
                    ],
                    'select' => ['DOMAIN', 'EXPORTED', 'FIELDS'],
                    'limit' => self::DB_SELECT_LIMIT,
                    'offset' => (self::DB_SELECT_LIMIT * $i)
                ]
            );

            $resultCount = $dbResult->getSelectedRowsCount();
            if ($resultCount < self::DB_SELECT_LIMIT) {
                $isRowsEnds = true;
            }

            while ($arrResult = $dbResult->fetch()) {
                //
            }
        }
    }
}
