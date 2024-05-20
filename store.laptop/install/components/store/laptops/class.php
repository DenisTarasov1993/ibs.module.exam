<?php

use Bitrix\Main\Loader;
use CBitrixComponent;
use Store\Laptop\URL\SearchEngineFriendly;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die;
}

class StoreLaptops extends CBitrixComponent
{
    public function executeComponent()
    {
        Loader::includeModule('store.laptop');

        $engineObject = new SearchEngineFriendly(
            trim($this->arParams['~SEF_FOLDER']),
            $this->arParams['~SEF_URL_TEMPLATES']
        );
        
        $this->arResult['TYPE_PAGE'] = $engineObject->getTypePage();
        $this->arResult['DETAIL_ID'] = $engineObject->getDetailId();
        $this->arResult['BRAND_ID'] = $engineObject->getBrandId();
        $this->arResult['MODEL_ID'] = $engineObject->getModelId();
        
        $this->includeComponentTemplate(
            $this->arResult['TYPE_PAGE'] == 'detail' ? 'detail' : 'list'
        );
    }
    
}
    