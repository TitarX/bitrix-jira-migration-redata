<?php

namespace Dev\PerfCode\JiraMigrationReData\Workers;

use Dev\PerfCode\JiraMigrationReData\Models\IssueTable;

class IssueWorker extends Worker
{
    public static function run(): void
    {
        $dbResult = IssueTable::getList(
            [
                'order' => ['DOMAIN' => 'asc'],
                'filter' => [
                    'LOGIC' => 'OR',
                    'EXPORTED' => 'N',
                    'EXPORTED_SUBTASK' => 'N'
                ],
                'select' => ['DOMAIN', 'EXPORTED', 'FIELDS']
            ]
        );
    }
}
