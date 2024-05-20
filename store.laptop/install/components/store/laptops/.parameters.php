<?php

use \Bitrix\Main\Localization\Loc;

$arComponentParameters = [
	'PARAMETERS' => [
		'SEF_MODE' => [
            'start' => [
				'NAME' => Loc::getMessage('STORE_LAPTOPS_SEF_MODE_START'),
				'DEFAULT' => '',
				'VARIABLES' => [''],
			],
            'brand' => [
				'NAME' => Loc::getMessage('STORE_LAPTOPS_SEF_MODE_BRAND'),
				'DEFAULT' => '/#BRAND#/',
				'VARIABLES' => ['BRAND'],
			],
            'model' => [
				'NAME' => Loc::getMessage('STORE_LAPTOPS_SEF_MODE_MODEL'),
				'DEFAULT' => '/#BRAND#/#MODEL#/',
				'VARIABLES' => ['BRAND', 'MODEL'],
			],
			'detail' => [
				'NAME' => Loc::getMessage('STORE_LAPTOPS_SEF_MODE_DETAIL'),
				'DEFAULT' => '/detail/#NOTEBOOK#/',
				'VARIABLES' => ['NOTEBOOK'],
			],
		],
    ]
];
