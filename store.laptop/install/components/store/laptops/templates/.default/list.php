<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->IncludeComponent(
	'store:laptops.list', 
	'.default', 
	[
        'TYPE_PAGE' => $arResult['TYPE_PAGE'],
        'BRAND_ID' => $arResult['BRAND_ID'],
        'MODEL_ID' => $arResult['MODEL_ID'],
        'SEF_MODE' => $arParams['SEF_MODE'],
        'SEF_FOLDER' => $arParams['SEF_FOLDER'],
        'SEF_URL_TEMPLATES' => $arParams['SEF_URL_TEMPLATES']
	],
	false
);
