<?php

namespace Store\Laptop\InstallHelper;

use Bitrix\Main\Application;

class DBManager
{   
    /**
     * Генерация таблиц для модуля
     * @param array $tablesList - классы таблиц
     */
    public static function createTables(array $tablesList = []): void
    {
        foreach ($tablesList as $classTableName) {   
            if (static::hasTable($classTableName::getTableName())) {
                continue;
            }        
            $entity = $classTableName::getEntity();
            $entity->createDbTable();
        }
    }
    
    /**
     * Удаление таблиц для модуля
     * @param array $tablesList - классы таблиц
     */
    public static function dropTables(array $tablesList = []): void
    {
        $connection = Application::getConnection();
        foreach (array_reverse($tablesList) as $classTableName) {
            if (!static::hasTable($classTableName::getTableName())) {
                continue;
            }
            $connection->dropTable($classTableName::getTableName());
        }
    }
    
    /**
     * Проверка существования таблицы
     * @param string $tablesName - имя таблицы
     */
    private static function hasTable(string $tableName): bool
    {
        $connection = Application::getConnection();
        if ($connection->isTableExists($tableName)) {
            return true;
        }
        return false;
    }
    
}
