<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Store\Laptop\Helper\LaptopsList;

Loader::includeModule('store.laptop');

$arResult['NAV'] = LaptopsList::getNavParams($arResult['TOTAL_COUNT']);

$arResult['ROWS'] = [];

foreach ($arResult['LAPTOPS'] as $laptop) {
    $arResult['ROWS'][] = [
        'data' => [
            'ID' => $laptop['ID'],
            'MANUFACTURER' => $laptop['MANUFACTURER_NAME'],
            'MODEL' => $laptop['MODEL_NAME'],
            'NAME' => '<a href="'.$laptop['URL'].'">'.$laptop['NAME'].'</a>',
            'YEAR' => $laptop['YEAR'],
            'PRICE' => $laptop['PRICE'],
        ]
    ];
}
