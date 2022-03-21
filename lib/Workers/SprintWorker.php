<?php

namespace Dev\PerfCode\JiraMigrationReData\Workers;

use Dev\PerfCode\JiraMigrationReData\Models\SprintTable;

class SprintWorker
{
    public static function run()
    {
        $dbResult = SprintTable::getList(
            [
                'order' => ['DOMAIN' => 'asc'],
                'filter' => ['EXPORTED' => 'N'],
                'select' => ['DOMAIN', 'EXPORTED', 'FIELDS']
            ]
        );
    }
}
