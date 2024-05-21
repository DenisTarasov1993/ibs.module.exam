<?php

namespace Store\Laptop\DBProvider\ORM;

use Bitrix\Main\Context;
use Store\Laptop\DBProvider\ORM\StoreLaptopTable;
use Store\Laptop\DBProvider\ORM\StoreManufacturerTable;
use Store\Laptop\DBProvider\ORM\StoreModelTable;
use Store\Laptop\Helper\LaptopsList;

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
        
        $request = Context::getCurrent()
            ->getRequest()
        ;
        $order = ['ID' => 'ASC'];
        if (
            ($request->get('by') == 'PRICE' || $request->get('by') == 'YEAR')
            && ($request->get('order') == 'desc' || $request->get('order') == 'asc')
        ) {
            $order = [$request->get('by') => $request->get('order')];
        }
        foreach (LaptopsList::getFilter() as $keyFilter => $valueParam) {
            $filter[$keyFilter] = $valueParam;
        }
        $nav = LaptopsList::getNavParams();  
        
        $laptopObject = StoreLaptopTable::getList([
            'order' => $order,
            'select' => [
                'ID', 'NAME', 'CODE', 'PRICE', 'YEAR', 'MODEL_NAME' => 'MODEL.NAME',
                'MANUFACTURER_NAME' => 'MODEL.MANUFACTURER.NAME'
            ],
            'filter' => $filter,
            'offset' => $nav->getOffset(),
            'limit' => $nav->getLimit(),
            'count_total' => true,
        ]);
            
        while ($laptop = $laptopObject->fetch()) {
            $objects[] = $laptop;
        }
        return [
            'LAPTOPS' => $objects,
            'TOTAL_COUNT' => $laptopObject->getCount(),
            'ONPAGE' => $onpage,
        ];
    }
    
    public function getModels(int $manufacturerId = 0): array
    {
        $models = [];
        $filter = [];
        if ($manufacturerId) {
            $filter = ['MANUFACTURER.ID' => $manufacturerId];
        }
        $object = StoreModelTable::getList([
            'select' => [
                'ID', 'NAME', 'CODE', 'MANUFACTURER.ID', 
                'BRAND_CODE' => 'MANUFACTURER.CODE'
            ],
            'filter' => $filter
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
    
    public static function isCodeHere(string $code = '', $object): int
    {
        $dbObject = $object::getList([
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
