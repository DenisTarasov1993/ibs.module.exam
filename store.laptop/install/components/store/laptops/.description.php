<?php

use Store\Laptop\Components\Settings\ParentWay;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loader::includeModule('store.laptop');

$arComponentDescription = [
	'NAME' => Loc::getMessage('STORE_LAPTOP_NAME'),
	'DESCRIPTION' => Loc::getMessage('STORE_LAPTOP_DESCRIPTION'),
	'ICON' => '',
    'COMPLEX' => 'Y',
	'CACHE_PATH' => 'Y',
	'SORT' => 20,
	'PATH' => ParentWay::getPathForDescription(),
];
