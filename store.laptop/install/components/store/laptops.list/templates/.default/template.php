<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

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
<div class="row">
    <div class="col-xs-12"><?php 
        if (!empty($arResult['LAPTOPS'])) {
            ?><h2 class="bx-title"><?php echo Loc::getMessage('LAPTOPS_LIST_LAPTOPS');?></h2><?php
            ?><div class="row"><?php
            foreach ($arResult['LAPTOPS'] as $laptop) {
                ?><div class="col-xs-4">
                    <p><a href="<?php echo $laptop['URL'];?>"><?php echo $laptop['NAME'];?></a></p>
                </div><?php
            }
            ?></div><?php
        }
?></div>
</div>