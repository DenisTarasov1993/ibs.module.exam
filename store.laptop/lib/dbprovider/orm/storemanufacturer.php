<?php

namespace Store\Laptop\DBProvider\ORM;

class StoreManufacturer extends EO_StoreManufacturer
{
    public static function isCodeHere(string $code = ''): int
    {
        $dbObject = StoreManufacturerTable::getList([
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
    
}
