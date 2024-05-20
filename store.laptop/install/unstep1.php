<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<form action="<?php echo $APPLICATION->GetCurPage();?>" method="POST" name="form1">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?php echo LANG;?>">
    <input type="hidden" name="id" value="store.laptop">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="1">
    <table cellpadding="3" cellspacing="0" border="0" width="0%">
        <tr>
            <td>&nbsp;</td>
            <td>
                <table cellpadding="3" cellspacing="0" border="0">
                    <tr>
                        <td><input type="checkbox" checked name="clear_db" id="clear_db" value="Y"></td>
                        <td>
                            <p>
                                <label for="default_db">
                                    <?php echo Loc::getMessage('STORE_LAPTOP_UNSTEP_1_DEFAULT_DB');?>
                                </label>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <input type="submit" name="inst" value="<?php echo Loc::getMessage('MOD_UNINSTALL');?>">
</form>
