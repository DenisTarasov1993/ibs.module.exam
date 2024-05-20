<?php

namespace Store\Laptop\DBProvider\ORM;

use Store\Laptop\DBProvider\ORM\StoreLaptopTable;
use Store\Laptop\DBProvider\ORM\StoreManufacturerTable;
use Store\Laptop\DBProvider\ORM\StoreModelTable;

class LaptopList
{
    public function getLaptops(int $manufacturerId = 0, int $modelId = 0): array
    {
        $objects = [];
        $filter = [];
        if ($modelId) {
            $filter['MODEL.ID'] = $modelId;
        }
        if ($manufacturerId) {
            $filter['MODEL.MANUFACTURER.ID'] = $manufacturerId;
        }
        $laptopObject = StoreLaptopTable::getList([
            'select' => ['ID', 'NAME', 'CODE'],
            'filter' => $filter,
        ]);
        while ($laptop = $laptopObject->fetch()) {
            $objects[] = $laptop;
        }
        return $objects;
    }
    
    public function getModels(int $manufacturerId = 0): array
    {
        $models = [];
        $object = StoreModelTable::getList([
            'select' => [
                'ID', 'NAME', 'CODE', 'MANUFACTURER.ID', 
                'BRAND_CODE' => 'MANUFACTURER.CODE'
            ],
            'filter' => ['MANUFACTURER.ID' => $manufacturerId]
        ]);
        while ($model = $object->fetch()) {
            $models[] = $model;
        }
        return $models;
    }
    
    public function getManufacturers(): array
    {
        $manufacturers = [];
        $object = StoreManufacturerTable::getList([
            'select' => ['ID', 'NAME', 'CODE'],
        ]);
        while ($manufacturer = $object->fetch()) {
            $manufacturers[] = $manufacturer;
        }
        return $manufacturers;
    }
    
}
