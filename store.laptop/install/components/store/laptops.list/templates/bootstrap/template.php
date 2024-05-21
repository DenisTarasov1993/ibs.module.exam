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
    <div class="col-xs-8"><?php
        $tag = $arResult['SORT_STRING'] == '&by=YEAR&order=ASC' ? 'span' : 'a';
        ?>
        <<?php echo $tag;?> href="<?php
            echo $arResult['NOW_DIRECTORY'];
        ?>?by=YEAR&order=ASC<?php
            echo $arResult['ONPAGE_STRING'].$arResult['PAGE_STRING'];
        ?>"><?php echo Loc::getMessage('LAPTOPS_LIST_SORT_OLD');?></<?php echo $tag;?>><?php
        $tag = $arResult['SORT_STRING'] == '&by=YEAR&order=DESC' ? 'span' : 'a';
        ?>
        <<?php echo $tag;?> href="<?php
            echo $arResult['NOW_DIRECTORY'];
        ?>?by=YEAR&order=DESC<?php
            echo $arResult['ONPAGE_STRING'].$arResult['PAGE_STRING'];
        ?>"><?php echo Loc::getMessage('LAPTOPS_LIST_SORT_NEW');?></<?php echo $tag;?>><?php
        $tag = $arResult['SORT_STRING'] == '&by=PRICE&order=ASC' ? 'span' : 'a';
        ?>
        <<?php echo $tag;?> href="<?php
            echo $arResult['NOW_DIRECTORY'];
        ?>?by=PRICE&order=ASC<?php
            echo $arResult['ONPAGE_STRING'].$arResult['PAGE_STRING'];
        ?>"><?php echo Loc::getMessage('LAPTOPS_LIST_SORT_CHEAP');?></<?php echo $tag;?>><?php
        $tag = $arResult['SORT_STRING'] == '&by=PRICE&order=DESC' ? 'span' : 'a';
        ?>
        <<?php echo $tag;?> href="<?php
            echo $arResult['NOW_DIRECTORY'];
        ?>?by=PRICE&order=DESC<?php
            echo $arResult['ONPAGE_STRING'].$arResult['PAGE_STRING'];
        ?>"><?php echo Loc::getMessage('LAPTOPS_LIST_SORT_EXPENSIVE');?></<?php echo $tag;?>>
    </div>
    <div class="col-xs-4">
        <span><?php echo Loc::getMessage('LAPTOPS_LIST_ONPAGE');?> </span>
        <?php 
        $tag = $arResult['ONPAGE'] == 5 ? 'span' : 'a';
        ?>
        <<?php echo $tag;?> href="<?php
            echo $arResult['NOW_DIRECTORY'];
        ?>?onpage=5<?php echo $arResult['SORT_STRING'];?>">5</<?php echo $tag;?>>
        <?php 
        $tag = $arResult['ONPAGE'] == 10 ? 'span' : 'a';
        ?>
        <<?php echo $tag;?> href="<?php
            echo $arResult['NOW_DIRECTORY'];
        ?>?onpage=10<?php echo $arResult['SORT_STRING'];?>">10</<?php echo $tag;?>>
        <?php 
        $tag = $arResult['ONPAGE'] == 20 ? 'span' : 'a';
        ?>
        <<?php echo $tag;?> href="<?php
            echo $arResult['NOW_DIRECTORY'];
        ?>?onpage=20<?php echo $arResult['SORT_STRING'];?>">20</<?php echo $tag;?>>
    </div>
</div>
<div class="row">
    <div class="col-xs-12"><?php 
        if (!empty($arResult['LAPTOPS'])) {
            ?><h2 class="bx-title"><?php echo Loc::getMessage('LAPTOPS_LIST_LAPTOPS');?></h2><?php
            ?><div class="row"><?php
            foreach ($arResult['LAPTOPS'] as $laptop) {
                ?><div class="col-xs-4">
                    <p><a href="<?php echo $laptop['URL'];?>"><?php 
                        echo $laptop['MANUFACTURER_NAME'].' '.$laptop['MODEL_NAME'].' '.$laptop['NAME'];
                    ?></a></p>
                    <p><?php echo $laptop['YEAR'].' '.Loc::getMessage('LAPTOPS_LIST_YEAR');?>, <?php
                        echo $laptop['PRICE'].' '.Loc::getMessage('LAPTOPS_LIST_PRICE');
                    ?></p>
                </div><?php
            }
            ?></div><?php
        }
?></div>
</div>

<div class="row">
    <div class="col-xs-12"><?php
        for ($page = 1; $page <= $arResult['PAGES']; $page ++) {
            if ($arResult['PAGE_STRING'] == '&page='.$page || ($page == 1 && !$arResult['PAGE_STRING'])) {
                ?><span><?php echo $page;?></span>&nbsp;&nbsp;<?php
            } else {
            ?><a href="<?php 
                echo $arResult['NOW_DIRECTORY']?>?page=<?php 
                echo $page;?><?php 
                echo $arResult['SORT_STRING'].$arResult['ONPAGE_STRING'];?>"><?php 
                echo $page;
            ?></a>&nbsp;&nbsp;<?php
            }
        }
    ?></div>
</div>