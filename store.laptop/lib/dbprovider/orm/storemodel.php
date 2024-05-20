<?php

namespace Store\Laptop\DBProvider\ORM;

class StoreModel extends EO_StoreModel
{
    public static function isCodeHere(string $code = ''): int
    {
        $dbObject = StoreModelTable::getList([
            'filter' => [
                '=CODE' => htmlspecialcharsbx($code),
            ],
            'select' => ['ID']
        ]);
        if ($object = $dbObject->fetch()) {
            return $object['ID'];
        }
        return 0;
    }
    
    public static function getParentManufacturer(int $modelId = 0): int
    {
        $modelObject = StoreModelTable::getByPrimary($modelId, [
            'select' => ['ID', 'MANUFACTURER.ID']
        ])->fetchObject();
        return intval($modelObject->getManufacturer()->getId());
    }
    
}
