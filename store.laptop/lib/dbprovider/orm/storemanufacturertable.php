<?php

namespace Store\Laptop\DBProvider\ORM;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DataManager;

class StoreManufacturerTable extends DataManager
{
    public static function getTableName()
	{
		return 'store_manufacturer';
	}
    
    public static function getMap()
	{
		return [
			new IntegerField('ID', [
                'primary' => true,
				'autocomplete' => true
            ]),
			new StringField('NAME'),
			new StringField('CODE'),
		];
	}
    
    public static function getObjectClass()
	{
		return StoreManufacturer::class;
	}
    
}
