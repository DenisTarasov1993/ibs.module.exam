<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Store\Laptop\Helper\LaptopsList;

$this->addExternalCss('/bitrix/css/main/bootstrap.css');

?>
<div class="row">
    <div class="col-xs-12"><?php 
        if (!empty($arResult['BRANDS'])) {
            ?><h1 class="bx-title"><?php echo Loc::getMessage('LAPTOPS_LIST_MANUFACTURERS');?></h1><?php
            foreach ($arResult['BRANDS'] as $brand) {
                ?><p><a href="<?php echo $brand['URL'];?>"><?php echo $brand['NAME'];?></a></p><?php
            }
        }
        if (!empty($arResult['MODELS'])) {
            ?><h1 class="bx-title"><?php echo Loc::getMessage('LAPTOPS_LIST_MODELS');?></h1><?php
            foreach ($arResult['MODELS'] as $model) {
                ?><p><a href="<?php echo $model['URL'];?>"><?php echo $model['NAME'];?></a></p><?php
            }
        }
?></div>
</div>
<?php
$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [ 
    'FILTER_ID' => 'report_list', 
    'GRID_ID' => 'report_list', 
    'FILTER' => LaptopsList::getFilterFields(), 
    'ENABLE_LIVE_SEARCH' => true, 
    'ENABLE_LABEL' => true 
]);?>
<div class="row">
    <div class="col-xs-12">
        <h2><?php echo Loc::getMessage('LAPTOPS_LIST_LAPTOPS');?></h2>
    </div>
</div>
<?php
$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', [
    'GRID_ID' => 'report_list',
    'COLUMNS' => LaptopsList::getColumns(),
    'ROWS' => $arResult['ROWS'],
    'SHOW_ROW_CHECKBOXES' => false,
    'NAV_OBJECT' => $arResult['NAV'],
    'AJAX_MODE' => 'Y',
    'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
    'PAGE_SIZES' => [
        ['NAME' => "5", 'VALUE' => '5'],
        ['NAME' => '10', 'VALUE' => '10'],
        ['NAME' => '20', 'VALUE' => '20'],
    ],
    'AJAX_OPTION_JUMP'          => 'N',
    'SHOW_CHECK_ALL_CHECKBOXES' => false,
    'SHOW_ROW_ACTIONS_MENU'     => false,
    'SHOW_GRID_SETTINGS_MENU'   => false,
    'SHOW_NAVIGATION_PANEL'     => true,
    'SHOW_PAGINATION'           => true,
    'SHOW_SELECTED_COUNTER'     => false,
    'SHOW_TOTAL_COUNTER'        => false,
    'SHOW_PAGESIZE'             => true,
    'SHOW_ACTION_PANEL'         => false,
    'ACTION_PANEL'              => [
        'GROUPS' => [
        ],
    ],
    'ALLOW_COLUMNS_SORT'        => true,
    'ALLOW_COLUMNS_RESIZE'      => true,
    'ALLOW_HORIZONTAL_SCROLL'   => true,
    'ALLOW_SORT'                => true,
    'ALLOW_PIN_HEADER'          => true,
    'AJAX_OPTION_HISTORY'       => 'N'
]);
