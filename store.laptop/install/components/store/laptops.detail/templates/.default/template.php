<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->addExternalCss('/bitrix/css/main/bootstrap.css');

?>
<div class="row">
    <div class="col-xs-12">
        <h1 class="bx-title"><?php echo $arResult['MANUFACTURER'].' '.$arResult['MODEL'].': '.$arResult['NAME'];?></h1>
        <p>id: <?php echo $arResult['ID'];?></p>
        <p>Наименование ноутбука: <?php echo $arResult['NAME'];?></p>
        <p>Производитель: <?php echo $arResult['MANUFACTURER'];?></p>
        <p>Модель: <?php echo $arResult['MODEL'];?></p>
        <p>Год выпуска: <?php echo $arResult['YEAR'];?></p>
        <p>Цена: <?php echo $arResult['PRICE'];?> у.е.</p>
        <?php if (!empty($arResult['PROPERTIES'])) {?>
            <h2>Опции:</h2>
            <ul>
                <?php foreach ($arResult['PROPERTIES'] as $property) {
                    ?><li><?php echo $property;?></li><?php
                }?>
            </ul>
        <?php } ?>
    </div>
</div>