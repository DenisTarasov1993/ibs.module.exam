<?php

namespace Store\Laptop\Helper;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Grid\Options;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\UI\Filter\Options as FilterOption;
use Store\Laptop\DBProvider\ORM\LaptopList;

class LaptopsList
{
    /**
     * id контейнера grid компонента
     */
    private static $gridId = 'report_list';
    /**
     * Столбцы таблицы
     */
    private static array $tableColumns = [
        ['id' => 'ID', 'name' => 'LAPTOPS_LIST_ID', 'default' => true],
        ['id' => 'MANUFACTURER', 'name' => 'LAPTOPS_LIST_MANUFACTURER', 'default' => true],
        ['id' => 'MODEL', 'name' => 'LAPTOPS_LIST_MODEL', 'default' => true],
        ['id' => 'NAME', 'name' => 'LAPTOPS_LIST_NAME', 'default' => true],
        ['id' => 'YEAR', 'name' => 'LAPTOPS_LIST_YEAR', 'sort' => 'YEAR', 'default' => true],
        ['id' => 'PRICE', 'name' => 'LAPTOPS_LIST_PRICE', 'sort' => 'PRICE', 'default' => true],
    ];
    /**
     * Набор доступных полей фильтра
     */
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
    
    /**
     * Перевод нейминга в массиве в пользовательское название
     * @param array $items - массив с параметрами
     * @return array - массив с подменёнными языковыми фразами
     */
    private static function setLocalization(array $items = []): array
    {
        foreach ($items as $keyItem => $item) {
            $items[$keyItem]['name'] = Loc::getMessage($item['name']);
        }
        return $items;
    }
    
    /**
     * формирование вариантов выбора для фильтра
     * @param array $items - массив с полными параметрами
     * @return array - массив вариантов воспринимаемый grid-фильтром
     */
    private static function itemsFormed(array $items = []): array
    {
        $formed = [];
        foreach ($items as $item) {
            $formed[$item['ID']] = $item['NAME'];
        }
        return $formed;
    }
    
    /**
     * запрос перечня производителей
     * @return array - массив производителей, для grid-фильтра
     */
    private static function getManufacturer(): array
    {
        return static::itemsFormed(LaptopList::getManufacturers());
    }
    
    /**
     * запрос перечня моделей
     * @return array - массив моделей, для grid-фильтра
     */
    private static function getModels(): array
    {
        return static::itemsFormed(LaptopList::getModels());
    }

    /**
     * Получение доступных полей для фильтра
     * @return array - массив полей фильтра
     */    
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
    
    /**
     * формирование постранички для grid-компонента
     * @param int $count - общее число строк
     * @return PageNavigation - объект постранички
     */
    public static function getNavParams(int $count = 0): PageNavigation
    {
        $gridOptions = new Options(static::getContainerId());
        $sort = $gridOptions->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
        $navParams = $gridOptions->GetNavParams();

        $nav = new PageNavigation(static::getContainerId());
        $nav->allowAllRecords(false)
            ->setPageSize($navParams['nPageSize'])
            ->initFromUri();
        if ($count) {
            $nav->setRecordCount($count);
        }
        return $nav;
    }
    
    /**
     * Получение списка столбцов для grid-таблицы
     * @return PageNavigation - столбцы с языковыми фразами
     */
    public static function getColumns(): array
    {
        return static::setLocalization(static::$tableColumns);
    }
    
    /**
     * формирование фильтра для grid-компонента в воспринимаемом виде
     * @return array - набор условий
     */
    public static function getFilter(): array
    {
        $filterLaptop = [];
        $filterData = (new FilterOption(static::getContainerId()))->getFilter([]);
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
    
    /**
     * grid - id для компонента
     * @return array - набор условий
     */
    public static function getContainerId(): string
    {
        return static::$gridId;
    }

}
