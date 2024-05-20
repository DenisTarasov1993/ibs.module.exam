<?php

namespace Store\Laptop\DBProvider\ORM;

class StoreLaptop extends EO_StoreLaptop
{
    public static function isCodeHere(string $code = ''): int
    {
        $dbObject = StoreLaptopTable::getList([
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
