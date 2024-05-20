<?php

namespace Store\Laptop\DBProvider\ORM;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Store\Laptop\DBProvider\ORM\StoreManufacturerTable;

class StoreModelTable extends DataManager
{
    public static function getTableName()
	{
		return 'store_model';
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
            new IntegerField('MANUFACTURER_ID',
                ['required' => true]
            ),
            (new Reference(
                'MANUFACTURER',
                StoreManufacturerTable::class,
                Join::on('this.MANUFACTURER_ID', 'ref.ID')
            ))
		];
	}
       
    public static function getObjectClass()
	{
		return StoreModel::class;
	}
    
}
