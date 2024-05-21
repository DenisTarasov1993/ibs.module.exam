<?php

namespace Store\Laptop\Helper;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Grid\Options;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\UI\Filter\Options as FilterOption;
use Store\Laptop\DBProvider\ORM\LaptopList;

class LaptopsList
{
    private static array $tableColumns = [
        ['id' => 'ID', 'name' => 'LAPTOPS_LIST_ID', 'default' => true],
        ['id' => 'MANUFACTURER', 'name' => 'LAPTOPS_LIST_MANUFACTURER', 'default' => true],
        ['id' => 'MODEL', 'name' => 'LAPTOPS_LIST_MODEL', 'default' => true],
        ['id' => 'NAME', 'name' => 'LAPTOPS_LIST_NAME', 'default' => true],
        ['id' => 'YEAR', 'name' => 'LAPTOPS_LIST_YEAR', 'sort' => 'YEAR', 'default' => true],
        ['id' => 'PRICE', 'name' => 'LAPTOPS_LIST_PRICE', 'sort' => 'PRICE', 'default' => true],
    ];
    private static array $filterFields = [
        [
            'id' => 'MODEL.ID', 
            'name' => 'LAPTOPS_LIST_MODEL', 
            'type' => 'list',
            'items' => [],
        ], 
        [
            'id' => 'MODEL.MANUFACTURER.ID', 
            'name' => 'LAPTOPS_LIST_MANUFACTURER', 
            'type' => 'list', 
            'items' => [],
        ],
        [
            'id' => 'YEAR',
            'name' => 'LAPTOPS_LIST_YEAR',
            'type' => 'number'
        ],        
        [
            'id' => 'PRICE',
            'name' => 'LAPTOPS_LIST_PRICE',
            'type' => 'number'
        ]
    ];
    
    private static function setLocalization(array $items = []): array
    {
        foreach ($items as $keyItem => $item) {
            $items[$keyItem]['name'] = Loc::getMessage($item['name']);
        }
        return $items;
    }
    
    private static function itemsFormed(array $items = []): array
    {
        $formed = [];
        foreach ($items as $item) {
            $formed[$item['ID']] = $item['NAME'];
        }
        return $formed;
    }
    
    private static function getManufacturer(): array
    {
        return static::itemsFormed(LaptopList::getManufacturers());
    }
    
    private static function getModels(): array
    {
        return static::itemsFormed(LaptopList::getModels());
    }
    
    public static function getFilterFields()
    {
        foreach (static::$filterFields as $keyField => $params) {
            if ($params['id'] == 'MODEL.MANUFACTURER.ID') {
                static::$filterFields[$keyField]['items'] = static::getManufacturer();
            }
            if ($params['id'] == 'MODEL.ID') {
                static::$filterFields[$keyField]['items'] = static::getModels();
            }
        }
        return static::setLocalization(static::$filterFields);
    }
    
    public static function getNavParams(int $count = 0): PageNavigation
    {
        $gridOptions = new Options('report_list');
        $sort = $gridOptions->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
        $navParams = $gridOptions->GetNavParams();

        $nav = new PageNavigation('report_list');
        $nav->allowAllRecords(false)
            ->setPageSize($navParams['nPageSize'])
            ->initFromUri();
        if ($count) {
            $nav->setRecordCount($count);
        }
        return $nav;
    }
    
    public static function getColumns(): array
    {
        return static::setLocalization(static::$tableColumns);
    }
    
    public static function getFilter(): array
    {
        $filterLaptop = [];
        $filterData = (new FilterOption('report_list'))->getFilter([]);
        if (array_key_exists('FIND', $filterData)) {
            $filterLaptop['NAME'] = "%".$filterData['FIND']."%";
        }
        foreach (static::$filterFields as $params) {            
            if (
                array_key_exists($params['id'].'_from', $filterData) 
                && array_key_exists($params['id'].'_to', $filterData)
            ) {
                $filterLaptop['><'.$params['id']] = [$filterData[$params['id'].'_from'], $filterData[$params['id'].'_to']];
            }
            if (!array_key_exists($params['id'], $filterData)) {
                continue;
            }
            $filterLaptop[$params['id']] = $filterData[$params['id']];
        }
        return $filterLaptop;
    }
        
}
