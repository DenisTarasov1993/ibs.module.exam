<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->IncludeComponent(
	'store:laptops.detail', 
	'.default', 
	[
        'DETAIL_ID' => $arResult['DETAIL_ID']
	],
	false
);
