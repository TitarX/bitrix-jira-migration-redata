<?php

namespace Dev\PerfCode\JiraMigrationReData\Models;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;

class SprintTable extends Entity\DataManager
{
    /**
     * Метод возвращает имя таблицы
     *
     * @access public
     * @static
     *
     * @return string Имя таблицы
     */
    public static function getTableName()
    {
        return 'bx24_sprint_jiramigration';
    }

    /**
     * Метод возвращает карту полей таблицы базы данных
     *
     * @access public
     * @static
     *
     * @return array Массив объектов, описывающих поля таблицы в базе данных
     * @throws \Bitrix\Main\SystemException
     */
    public static function getMap()
    {
        return [
            new Entity\StringField('DOMAIN'),
            new Entity\StringField(
                'MEMBER_ID',
                [
                    'primary' => true,
                ]
            ),
            new Entity\StringField(
                'JIRA_ID',
                [
                    'primary' => true,
                ]
            ),
            new Entity\StringField(
                'JIRA_NAME',
                [
                    'default_value' => '',
                ]
            ),
            new Entity\IntegerField(
                'PROJECT_JIRA_ID',
                [
                    'default_value' => 0,
                ]
            ),
            new Entity\IntegerField(
                'BX_ID',
                [
                    'default_value' => 0,
                ]
            ),
            new Entity\TextField(
                'FIELDS',
                [
                    'serialized' => true,
                ]
            ),
            new Entity\BooleanField(
                'EXPORTED',
                [
                    'values' => ['N', 'Y', 'E'],
                    'default_value' => 'N',
                ]
            ),
            new Entity\DatetimeField(
                'DATE_CREATE',
                [
                    'default_value' => new Type\DateTime(),
                ]
            ),
            new Entity\DatetimeField(
                'DATE_UPDATE',
                [
                    'default_value' => new Type\DateTime(),
                ]
            ),
            new Entity\IntegerField('VERSION'),
            new Entity\IntegerField(
                'PROC_ID',
                [
                    'default_value' => 0,
                ]
            ),
            new Entity\StringField(
                'START_DATE',
                [
                    'default_value' => '',
                ]
            ),
            new Entity\StringField(
                'END_DATE',
                [
                    'default_value' => '',
                ]
            ),
            new Entity\StringField(
                'STATE',
                [
                    'default_value' => '',
                ]
            ),
        ];
    }
}
