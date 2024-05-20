<?php

namespace Store\Laptop\DBProvider\ORM;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Store\Laptop\DBProvider\ORM\StoreLaptopTable;
use Store\Laptop\DBProvider\ORM\StorePropertyTable;

class StoreLaptopPropertyTable extends DataManager
{
	public static function getTableName()
	{
		return 'store_laptop_property';
	}
    
    public static function getMap()
	{
		return [
			(new IntegerField('STORE_LAPTOP_ID'))
				->configurePrimary(true),
			(new Reference('STORE_LAPTOP', StoreLaptopTable::class,
				Join::on('this.STORE_LAPTOP_ID', 'ref.ID')))
				->configureJoinType('inner'),
			(new IntegerField('STORE_PROPERTY_ID'))
				->configurePrimary(true),
			(new Reference('STORE_PROPERTY', StorePropertyTable::class,
				Join::on('this.STORE_PROPERTY_ID', 'ref.ID')))
				->configureJoinType('inner'),
		];
	}
    
}
