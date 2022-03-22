<?php

namespace Dev\PerfCode\JiraMigrationReData\Workers;

use Dev\PerfCode\JiraMigrationReData\Models\CommentTable;

class CommentWorker
{
    public static function run(): void
    {
        $dbResult = CommentTable::getList(
            [
                'order' => ['DOMAIN' => 'asc'],
                'filter' => ['EXPORTED' => 'N'],
                'select' => ['DOMAIN', 'EXPORTED', 'FIELDS']
            ]
        );
    }
}
