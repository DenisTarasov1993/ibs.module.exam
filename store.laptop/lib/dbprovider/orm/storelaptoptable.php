<?php

namespace Store\Laptop\DBProvider\ORM;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Query\Join;
use Store\Laptop\DBProvider\ORM\StoreModelTable;
use Store\Laptop\DBProvider\ORM\StorePropertyTable;

class StoreLaptopTable extends DataManager
{
    public static function getTableName()
	{
		return 'store_laptop';
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
			new IntegerField('YEAR'),
			new IntegerField('PRICE'),
            new IntegerField('MODEL_ID',
                ['required' => true]
            ),
            (new Reference(
                'MODEL',
                StoreModelTable::class,
                Join::on('this.MODEL_ID', 'ref.ID')
            )),
            (new ManyToMany('PROPERTY', StorePropertyTable::class))
				->configureTableName('store_laptop_property')
		];
	}
    
    public static function getObjectClass()
	{
		return StoreLaptop::class;
	}
    
}
