<?php

namespace Store\Laptop\DBProvider\ORM;

use Store\Laptop\DBProvider\ORM\StoreLaptopTable;

class LaptopPage
{
    /**
     * Детальная информация по ноутбуку
     * @param int $laptopId - id ноутбука
     * @return array - набор полей в т.ч. связанных у сущности ноутбука
     */
    public function getById(int $laptopId = 0): array
    {
        $laptopObject = StoreLaptopTable::getByPrimary($laptopId, [
            'select' => ['*', 'PROPERTY', 'MODEL.NAME', 'MODEL.MANUFACTURER.NAME']
        ])->fetchObject();

        $object = [
            'ID' => $laptopObject->get('ID'),
            'NAME' => $laptopObject->get('NAME'),
            'YEAR' => $laptopObject->get('YEAR'),
            'PRICE' => $laptopObject->get('PRICE'),
            'MODEL' => $laptopObject->getModel()->getName(),
            'MANUFACTURER' => $laptopObject->getModel()->getManufacturer()->getName(),
            'PROPERTIES' => []
        ];
        
        foreach ($laptopObject->getProperty() as $property) {
            $object['PROPERTIES'][] = $property->getName();
        }
        return $object;
    }
    
}
