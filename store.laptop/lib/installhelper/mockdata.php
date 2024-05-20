<?php

namespace Store\Laptop\InstallHelper;

use Cutil;
use Bitrix\Main\Localization\Loc;
use Store\Laptop\DBProvider\ORM\StoreManufacturer;
use Store\Laptop\DBProvider\ORM\StoreManufacturerTable;
use Store\Laptop\DBProvider\ORM\StoreModel;
use Store\Laptop\DBProvider\ORM\StoreModelTable;
use Store\Laptop\DBProvider\ORM\StoreLaptop;
use Store\Laptop\DBProvider\ORM\StoreLaptopTable;
use Store\Laptop\DBProvider\ORM\StoreProperty;
use Store\Laptop\DBProvider\ORM\StorePropertyTable;

class MockData
{
    const PRICE_FROM = 99;
    const PRICE_TO = 400;
    const YEAR_FROM = 2020;
    const YEAR_TO = 2024;
    private static $manufacturerList = [
        'HP',
        'Samsung',
        'Lenovo',
        'Asus',
        'Acer',
    ];
    private static $modelList = [
        'TUF Gaming',
        'Aquilon',
        'GemiBook',
        'Vivobook',
        'IdeaPad',
        'Atlas',
        'ExpertBook',
        'Aspire',
    ];
    
    public static function generateDefault(): void
    {
        static::createManufacturers();
        static::createModels();
        static::createLaptops();
        static::createProperty();
        static::setRelation();
    }
    
    private static function createManufacturers(): void
    {
        foreach (static::$manufacturerList as $manufacturerName) {
            $manufacturer = new StoreManufacturer();
            $manufacturer->set('NAME', $manufacturerName);
            $manufacturer->set('CODE', static::getCode($manufacturerName));
            $manufacturer->save();
            unset($manufacturer);
        }
    }
    
    private static function createModels(): void
    {
        $manufacturerIds = static::getIds(StoreManufacturerTable::class);
        foreach (static::$modelList as $modelName) {
            $linkId = $manufacturerIds[array_rand($manufacturerIds)];
            $model = new StoreModel();
            $model->set('NAME', $modelName);
            $model->set('CODE', static::getCode($modelName));
            $model->set('MANUFACTURER_ID', $linkId);
            $model->save();
            unset($model);
        }
    }
    
    private static function createLaptops(): void
    {
        $modelIds = static::getIds(StoreModelTable::class);
        for ($num = 1; $num <= 60; $num ++) {
            $linkId = $modelIds[array_rand($modelIds)];
            $laptop = new StoreLaptop();
            $name = Loc::getMessage('MOCK_DATA_PRODUCT_NAME', ['#NUM#' => $num]);
            $laptop->set('NAME', $name);
            $laptop->set('CODE', static::getCode($name));
            $laptop->set('YEAR', rand(static::YEAR_FROM, static::YEAR_TO));
            $laptop->set('PRICE', rand(static::PRICE_FROM, static::PRICE_TO) * 100);
            $laptop->set('MODEL_ID', $linkId);
            $laptop->save();
            unset($laptop);
        }
    }
    
    private static function createProperty(): void
    {
        for ($num = 1; $num < 15; $num ++) {
            $laptop = new StoreProperty();
            $name = Loc::getMessage('MOCK_DATA_PROPERTY', ['#NUM#' => $num]);
            $laptop->set('NAME', $name);
            $laptop->save();
        }
    }
    
    public static function setRelation(): void
    {
        $laptopIds = static::getIds(StoreLaptopTable::class);
        $propertyIds = static::getIds(StorePropertyTable::class);
        foreach ($laptopIds as $laptopId) {
            $randomProperty = static::getRandomIds($propertyIds);
            $laptopObject = StoreLaptopTable::getByPrimary($laptopId)
                ->fetchObject();
            foreach ($randomProperty as $propertyId) {
                $propertyObject = StorePropertyTable::getByPrimary($propertyId)
                    ->fetchObject();
                $laptopObject->addToProperty($propertyObject);             
                $laptopObject->save();
            }
        }
    }
    
    private static function getRandomIds(array $ids = []): array
    {
        $randomKeys = array_rand($ids, rand(1, (count($ids) - 1)));
        
        if (is_array($randomKeys)) {
            $selectedIds = [];
            foreach ($randomKeys as $keyNum) {
                $selectedIds[] = $ids[$keyNum];
            }
            return $selectedIds;
        }
        return [$ids[$randomKeys]];
    }
    
    private static function getIds($tableClass): array
    {
        $dbOb = $tableClass::getList([
            'select' => ['ID']
        ]);
        $ids = [];
        while ($object = $dbOb->fetch()) {
            $ids[] = $object['ID'];
        }
        return $ids;
    }
    
    private static function getCode(string $name = ''): string
    {
        return Cutil::translit(
            $name, 
            'ru', 
            [
                'replace_space' => '-', 
                'replace_other' => '-'
            ]
        );
    }
    
}
