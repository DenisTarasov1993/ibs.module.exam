<?php

namespace Store\Laptop\Components\Settings;

use \Bitrix\Main\Localization\Loc;

class ParentWay
{
    private static $parentId = 'store_laptop';
    private static $parentSort = 1000;
    
    public static function getPathForDescription(): array
    {
        Loc::loadMessages(__FILE__);
        
        return [
            'ID' => self::$parentId,
            'NAME' => Loc::getMessage('STORE_LAPTOP_PARENT_BLOCK_NAME'),
            'SORT' => self::$parentSort,
        ];
    }
    
}
