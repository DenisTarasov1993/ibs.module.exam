<?php

use Bitrix\Main\Loader;
use CBitrixComponent;
use Store\Laptop\DBProvider\ORM\LaptopPage;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die;
}

class StoreLaptopsDetail extends CBitrixComponent
{
    public function executeComponent()
    {
        Loader::includeModule('store.laptop');
        
        $this->arResult = (new LaptopPage())
            ->getById($this->arParams['DETAIL_ID'])
        ;
        
        $this->includeComponentTemplate();
    }
    
}
    