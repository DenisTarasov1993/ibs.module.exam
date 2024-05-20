<?php

use Bitrix\Main\Loader;
use CBitrixComponent;
use Store\Laptop\DBProvider\ORM\LaptopList;
use Store\Laptop\URL\SearchEngineFriendly;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die;
}

class StoreLaptopsList extends CBitrixComponent
{
    public function executeComponent()
    {
        Loader::includeModule('store.laptop');
        $lapObject = new LaptopList();
        
        if ($this->arParams['TYPE_PAGE'] == 'start') {
            $this->arResult['BRANDS'] = $lapObject->getManufacturers();
            foreach ($this->arResult['BRANDS'] as $keyBrand => $brand) {
                $this->arResult['BRANDS'][$keyBrand]['URL'] = SearchEngineFriendly::makeNormalUrl(
                    trim($this->arParams['~SEF_FOLDER']),
                    $this->arParams['~SEF_URL_TEMPLATES']['brand'],
                    [$brand['CODE']],
                    ['#BRAND#']
                );
            }
        } else if ($this->arParams['TYPE_PAGE'] == 'brand') {
            $this->arResult['MODELS'] = $lapObject->getModels($this->arParams['BRAND_ID']);
            foreach ($this->arResult['MODELS'] as $keyModel => $model) {
                $this->arResult['MODELS'][$keyModel]['URL'] = SearchEngineFriendly::makeNormalUrl(
                    trim($this->arParams['~SEF_FOLDER']),
                    $this->arParams['~SEF_URL_TEMPLATES']['model'],
                    [$model['CODE'], $model['BRAND_CODE']],
                    ['#MODEL#', '#BRAND#']
                );
            }
        }
        
        $this->arResult['LAPTOPS'] = $lapObject->getLaptops(
            intval($this->arParams['BRAND_ID']),
            intval($this->arParams['MODEL_ID'])
        );
        foreach ($this->arResult['LAPTOPS'] as $keyLap => $laptop) {
            $this->arResult['LAPTOPS'][$keyLap]['URL'] = SearchEngineFriendly::makeNormalUrl(                
                trim($this->arParams['~SEF_FOLDER']),
                $this->arParams['~SEF_URL_TEMPLATES']['detail'],
                [$laptop['CODE']],
                ['#NOTEBOOK#']
            );
        }
        
        $this->includeComponentTemplate();
    }
    
}
    