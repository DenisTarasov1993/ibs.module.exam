<?php

namespace Store\Laptop\DBProvider\ORM;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Store\Laptop\DBProvider\ORM\StoreLaptopTable;

class StorePropertyTable extends DataManager
{
    public static function getTableName()
	{
		return 'store_property';
	}
    
    public static function getMap()
	{
		return [
			new IntegerField('ID', [
                'primary' => true,
				'autocomplete' => true
            ]),
			new StringField('NAME'),
            (new ManyToMany('LAPTOP', StoreLaptopTable::class))
				->configureTableName('store_laptop_property')
		];
	}
    
    public static function getObjectClass()
	{
		return StoreProperty::class;
	}
    
}
