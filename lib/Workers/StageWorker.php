<?php

namespace Dev\PerfCode\JiraMigrationReData\Workers;

use Dev\PerfCode\JiraMigrationReData\Models\StageTable;

class StageWorker
{
    public static function run(): void
    {
        $dbResult = StageTable::getList(
            [
                'order' => ['DOMAIN' => 'asc'],
                'filter' => ['EXPORTED' => 'N'],
                'select' => ['DOMAIN', 'EXPORTED', 'FIELDS']
            ]
        );
    }
}
