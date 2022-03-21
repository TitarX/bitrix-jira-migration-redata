<?php

namespace Dev\PerfCode\JiraMigrationReData\Models;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;

class UsersTable extends Entity\DataManager
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
        return 'bx24_users_jiramigration';
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
            new Entity\IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true
                ]
            ),
            new Entity\StringField(
                'DOMAIN',
                [
                    'required' => false
                ]
            ),
            new Entity\StringField(
                'MEMBER_ID',
                [
                    'required' => false
                ]
            ),
            new Entity\StringField(
                'JIRA_ID',
                [
                    'required' => true
                ]
            ),
            new Entity\TextField(
                'JIRA_EMAIL',
                [
                    'required' => false
                ]
            ),
            new Entity\TextField(
                'JIRA_NAME',
                [
                    'required' => false
                ]
            ),
            new Entity\TextField(
                'JIRA_PHOTO',
                [
                    'serialized' => true,
                    'required' => false
                ]
            ),
            new Entity\TextField(
                'JIRA_WORKSPACES',
                [
                    'serialized' => true,
                    'required' => false
                ]
            ),
            new Entity\TextField(
                'FIELDS',
                [
                    'serialized' => true,
                    'required' => false
                ]
            ),
            new Entity\IntegerField(
                'BX_USER_ID',
                [
                    'required' => false,
                    'default_value' => 0
                ]
            ),
            new Entity\BooleanField(
                'EXPORTED',
                [
                    'values' => ['N', 'Y', 'E'],
                    'default_value' => 'N',
                    'required' => false
                ]
            ),
            new Entity\DatetimeField(
                'DATE_CREATE',
                [
                    'default_value' => new Type\DateTime,
                    'required' => true
                ]
            ),
            new Entity\DatetimeField(
                'DATE_UPDATE',
                [
                    'default_value' => new Type\DateTime,
                    'required' => true
                ]
            ),
            new Entity\IntegerField(
                'APP_VERSION',
                [
                    'required' => false
                ]
            ),
            new Entity\IntegerField(
                'PROC_ID'
            ),
        ];
    }
}
