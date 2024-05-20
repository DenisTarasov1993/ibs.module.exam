<?php

use Store\Laptop\Components\Settings\ParentWay;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loader::includeModule('store.laptop');

$arComponentDescription = [
	'NAME' => Loc::getMessage('STORE_LAPTOP_DETAIL_NAME'),
	'DESCRIPTION' => Loc::getMessage('STORE_LAPTOP_DETAIL_DESCRIPTION'),
	'ICON' => '',
	'CACHE_PATH' => 'Y',
	'SORT' => 40,
	'PATH' => ParentWay::getPathForDescription(),
];
