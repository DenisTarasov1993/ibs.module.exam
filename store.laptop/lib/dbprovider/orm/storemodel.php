<?php

namespace Store\Laptop\DBProvider\ORM;

class StoreModel extends EO_StoreModel
{   
    /**
     * Получим id родительского производителя у модели
     * @param int $modelId - id модели
     * @return int - id производителя
     */
    public static function getParentManufacturer(int $modelId = 0): int
    {
        $modelObject = StoreModelTable::getByPrimary($modelId, [
            'select' => ['ID', 'MANUFACTURER.ID']
        ])->fetchObject();
        return intval($modelObject->getManufacturer()->getId());
    }
    
}
