<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

$this->addExternalCss('/bitrix/css/main/bootstrap.css');

?>
<div class="row">
    <div class="col-xs-12">
        <h1 class="bx-title"><?php echo $arResult['MANUFACTURER'].' '.$arResult['MODEL'].': '.$arResult['NAME'];?></h1>
        <p><?php echo Loc::getMessage('LAPTOPS_DETAIL_ID');?> <?php echo $arResult['ID'];?></p>
        <p><?php echo Loc::getMessage('LAPTOPS_DETAIL_NAME');?> <?php echo $arResult['NAME'];?></p>
        <p><?php echo Loc::getMessage('LAPTOPS_DETAIL_MANUFACTURER');?> <?php echo $arResult['MANUFACTURER'];?></p>
        <p><?php echo Loc::getMessage('LAPTOPS_DETAIL_MODEL');?> <?php echo $arResult['MODEL'];?></p>
        <p><?php echo Loc::getMessage('LAPTOPS_DETAIL_YEAR');?> <?php echo $arResult['YEAR'];?></p>
        <p><?php echo Loc::getMessage('LAPTOPS_DETAIL_PRICE', ['#PRICE#' => $arResult['PRICE']]);?></p>
        <?php if (!empty($arResult['PROPERTIES'])) {?>
            <h2><?php echo Loc::getMessage('LAPTOPS_DETAIL_PROPERTIES');?></h2>
            <ul>
                <?php foreach ($arResult['PROPERTIES'] as $property) {
                    ?><li><?php echo $property;?></li><?php
                }?>
            </ul>
        <?php } ?>
    </div>
</div>