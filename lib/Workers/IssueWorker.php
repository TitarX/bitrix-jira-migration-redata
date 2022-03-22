<?php

namespace Dev\PerfCode\JiraMigrationReData\Workers;

use Dev\PerfCode\JiraMigrationReData\Models\IssueTable;

class IssueWorker
{
    public static function run()
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
