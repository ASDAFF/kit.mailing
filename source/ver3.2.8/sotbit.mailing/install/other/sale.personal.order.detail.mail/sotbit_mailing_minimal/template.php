<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<?if(strlen($arResult["ERROR_MESSAGE"])):?>
    <?=ShowError($arResult["ERROR_MESSAGE"]);?>
<?else:?>	
    <?if($arParams["SHOW_ORDER_BASE"]=='Y' || $arParams["SHOW_ORDER_USER"]=='Y' || $arParams["SHOW_ORDER_PARAMS"]=='Y' || $arParams["SHOW_ORDER_BUYER"]=='Y' || $arParams["SHOW_ORDER_DELIVERY"]=='Y' || $arParams["SHOW_ORDER_PAYMENT"]=='Y'):?>
        <center style="width: 100%;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding: 25px 33px 27px 33px; border-top: 1px solid #d9d9d9; border-bottom: 1px solid #d9d9d9;">
                <thead>
                    <tr>
                        <td colspan="2" align="center">
                            <span class="name">
                                <?=GetMessage('SPOD_ORDER')?> <?=GetMessage('SPOD_NUM_SIGN')?><?=$arResult["ACCOUNT_NUMBER"]?>
                                <?if(strlen($arResult["DATE_INSERT_FORMATED"])):?>
                                    <?=GetMessage("SPOD_FROM")?> <?=$arResult["DATE_INSERT_FORMATED"]?>
                                <?endif?>
                            </span>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <?if($arParams["SHOW_ORDER_BASE"]=='Y'):?>
                        <tr>
                            <td width="30%" valign="top">
                                <span class="name">
                                    <?=GetMessage('SPOD_ORDER_STATUS')?>:
                                </span>
                            </td>
                            <td width="70%" valign="top">
                                <span class="value">
                                    <?=htmlspecialcharsbx($arResult["STATUS"]["NAME"])?>
                                    <?if(strlen($arResult["DATE_STATUS_FORMATED"])):?>
                                        (<?=GetMessage("SPOD_FROM")?> <?=$arResult["DATE_STATUS_FORMATED"]?>)
                                    <?endif?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="30%" valign="top">
                                <span class="name">
                                    <?=GetMessage('SPOD_ORDER_PRICE')?>:
                                </span>
                            </td>
                            <td width="70%" valign="top">
                                <span class="value">
                                    <?=$arResult["PRICE_FORMATED"]?>
                                    <?if(floatval($arResult["SUM_PAID"])):?>
                                        (<?=GetMessage('SPOD_ALREADY_PAID')?>:&nbsp;<?=$arResult["SUM_PAID_FORMATED"]?>)
                                    <?endif?>
                                </span>
                            </td>
                        </tr>

                        <?if($arResult["CANCELED"] == "Y" || $arResult["CAN_CANCEL"] == "Y"):?>
                            <tr>
                                <td width="30%" valign="top">
                                    <span class="name">
                                        <?=GetMessage('SPOD_ORDER_CANCELED')?>:
                                    </span>
                                </td>
                                <td width="70%" valign="top">
                                    <span class="value">
                                        <?if($arResult["CANCELED"] == "Y"):?>
                                            <?=GetMessage('SPOD_YES')?>
                                            <?if(strlen($arResult["DATE_CANCELED_FORMATED"])):?>
                                                (<?=GetMessage('SPOD_FROM')?> <?=$arResult["DATE_CANCELED_FORMATED"]?>)
                                            <?endif?>
                                        <?elseif($arResult["CAN_CANCEL"] == "Y"):?>
                                            <?=GetMessage('SPOD_NO')?>&nbsp;&nbsp;&nbsp;[<a href="<?=$arResult["URL_TO_CANCEL"]?>"><?=GetMessage("SPOD_ORDER_CANCEL")?></a>]
                                        <?endif?>
                                    </span>
                                </td>
                            </tr>
                        <?endif?>
                        <tr><td><br></td><td></td></tr>
                    <?endif?>
                        
                    <?if($arParams["SHOW_ORDER_USER"]=='Y'):?>
                        <?if(intval($arResult["USER_ID"])):?>
                            <?if(strlen($arResult["USER_NAME"])):?>
                                <tr>
                                    <td width="30%" valign="top">
                                        <span class="name">
                                            <?=GetMessage('SPOD_ACCOUNT')?>:
                                        </span>
                                    </td>
                                    <td width="70%" valign="top">
                                        <span class="value">
                                            <?=htmlspecialcharsbx($arResult["USER_NAME"])?>
                                        </span>
                                    </td>
                                </tr>
                            <?endif?>
                            <tr>
                                <td width="30%" valign="top">
                                    <span class="name">
                                        <?=GetMessage('SPOD_LOGIN')?>:
                                    </span>
                                </td>
                                <td width="70%" valign="top">
                                    <span class="value">
                                        <?=htmlspecialcharsbx($arResult["USER"]["LOGIN"])?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%" valign="top">
                                    <span class="name">
                                        <?=GetMessage('SPOD_EMAIL')?>:
                                    </span>
                                </td>
                                <td width="70%" valign="top">
                                    <span class="value">
                                        <?=htmlspecialcharsbx($arResult["USER"]["EMAIL"])?>
                                    </span>
                                </td>
                            </tr>

                            <tr><td><br></td><td></td></tr>
                        <?endif?>
                    <?endif?>

                    <?if($arParams["SHOW_ORDER_PARAMS"]=='Y'):?>
                        <tr>
                            <td width="30%" valign="top">
                                <span class="name">
                                    <?=GetMessage('SPOD_ORDER_PERS_TYPE')?>:
                                </span>
                            </td>
                            <td width="70%" valign="top">
                                <span class="value">
                                    <?=htmlspecialcharsbx($arResult["PERSON_TYPE"]["NAME"])?>
                                </span>
                            </td>
                        </tr>
                    <?endif?>
                    
                    <?if($arParams["SHOW_ORDER_BUYER"]=='Y'):?>
                        <?foreach($arResult["ORDER_PROPS"] as $prop):?>

                            <?if($prop["SHOW_GROUP_NAME"] == "Y"):?>

                                <tr><td><br></td><td></td></tr>

                            <?endif?>
                            <tr>
                                <td width="30%" valign="top">
                                    <span class="name">
                                        <?=$prop['NAME']?>:
                                    </span>
                                </td>
                                <td width="70%" valign="top">
                                    <span class="value">
                                        <?if($prop["TYPE"] == "Y/N"):?>
                                            <?=GetMessage('SPOD_'.($prop["VALUE"] == "Y" ? 'YES' : 'NO'))?>
                                        <?else:?>
                                            <?=htmlspecialcharsbx($prop["VALUE"])?>
                                        <?endif?>
                                    </span>
                                </td>
                            </tr>
                        <?endforeach?>

                        <?if(!empty($arResult["USER_DESCRIPTION"])):?>
                            <tr>
                                <td width="30%" valign="top">
                                    <span class="name">
                                        <?=GetMessage('SPOD_ORDER_USER_COMMENT')?>:
                                    </span>
                                </td>
                                <td width="70%" valign="top">
                                    <span class="value">
                                        <?=$arResult["USER_DESCRIPTION"]?>
                                    </span>
                                </td>
                            </tr>
                        <?endif?>

                        <tr><td><br></td><td></td></tr>
                    <?endif?>

                    <?if($arParams["SHOW_ORDER_PAYMENT"]=='Y'):?>
                        <tr>
                            <td width="30%" valign="top">
                                <span class="name">
                                    <?=GetMessage('SPOD_PAY_SYSTEM')?>:
                                </span>
                            </td>
                            <td width="70%" valign="top">
                                <span class="value">
                                    <?if(intval($arResult["PAY_SYSTEM_ID"])):?>
                                        <?=htmlspecialcharsbx($arResult["PAY_SYSTEM"]["NAME"])?>
                                    <?else:?>
                                        <?=GetMessage("SPOD_NONE")?>
                                    <?endif?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td width="30%" valign="top">
                                <span class="name">
                                    <?=GetMessage('SPOD_ORDER_PAYED')?>:
                                </span>
                            </td>
                            <td width="70%" valign="top">
                                <span class="value">
                                    <?if($arResult["PAYED"] == "Y"):?>
                                        <?=GetMessage('SPOD_YES')?>
                                        <?if(strlen($arResult["DATE_PAYED_FORMATED"])):?>
                                            (<?=GetMessage('SPOD_FROM')?> <?=$arResult["DATE_PAYED_FORMATED"]?>)
                                        <?endif?>
                                    <?else:?>
                                        <?=GetMessage('SPOD_NO')?>
                                        <?if($arResult["CAN_REPAY"]=="Y" && $arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y"):?>
                                            &nbsp;&nbsp;&nbsp;[<a href="<?=$arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]?>" target="_blank"><?=GetMessage("SPOD_REPEAT_PAY")?></a>]
                                        <?endif?>
                                    <?endif?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td width="30%" valign="top">
                                <span class="name">
                                    <?=GetMessage("SPOD_ORDER_DELIVERY")?>:
                                </span>
                            </td>
                            <td width="70%" valign="top">
                                <span class="value">
                                    <?if(strpos($arResult["DELIVERY_ID"], ":") !== false || intval($arResult["DELIVERY_ID"])):?>
                                        <?=htmlspecialcharsbx($arResult["DELIVERY"]["NAME"])?>

                                        <?if(intval($arResult['STORE_ID']) && !empty($arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']])):?>

                                            <?$store = $arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']];?>
                                            <div class="bx_ol_store">
                                                <div class="bx_old_s_row_title">
                                                    <?=GetMessage('SPOD_TAKE_FROM_STORE')?>: <b><?=$store['TITLE']?></b>

                                                    <?if(!empty($store['DESCRIPTION'])):?>
                                                        <div class="bx_ild_s_desc">
                                                            <?=$store['DESCRIPTION']?>
                                                        </div>
                                                    <?endif?>

                                                </div>
                                                
                                                <?if(!empty($store['ADDRESS'])):?>
                                                    <div class="bx_old_s_row">
                                                        <b><?=GetMessage('SPOD_STORE_ADDRESS')?></b>: <?=$store['ADDRESS']?>
                                                    </div>
                                                <?endif?>

                                                <?if(!empty($store['SCHEDULE'])):?>
                                                    <div class="bx_old_s_row">
                                                        <b><?=GetMessage('SPOD_STORE_WORKTIME')?></b>: <?=$store['SCHEDULE']?>
                                                    </div>
                                                <?endif?>

                                                <?if(!empty($store['PHONE'])):?>
                                                    <div class="bx_old_s_row">
                                                        <b><?=GetMessage('SPOD_STORE_PHONE')?></b>: <?=$store['PHONE']?>
                                                    </div>
                                                <?endif?>

                                                <?if(!empty($store['EMAIL'])):?>
                                                    <div class="bx_old_s_row">
                                                        <b><?=GetMessage('SPOD_STORE_EMAIL')?></b>: <a href="mailto:<?=$store['EMAIL']?>"><?=$store['EMAIL']?></a>
                                                    </div>
                                                <?endif?>
                                            </div>

                                        <?endif?>

                                    <?else:?>
                                        <?=GetMessage("SPOD_NONE")?>
                                    <?endif?>
                                </span>
                            </td>
                        </tr>

                        <?if($arResult["TRACKING_NUMBER"]):?>

                            <tr>
                                <td width="30%" valign="top">
                                    <span class="name">
                                        <?=GetMessage('SPOD_ORDER_TRACKING_NUMBER')?>:
                                    </span>
                                </td>
                                <td width="70%" valign="top">
                                    <span class="value">
                                        <?=$arResult["TRACKING_NUMBER"]?>
                                    </span>
                                </td>
                            </tr>

                            <tr><td><br></td><td></td></tr>

                        <?endif?>
                    <?endif?>
                </tbody>
            </table>
        </center>
    <?endif?>

    <?if($arParams["SHOW_ORDER_BASKET"]=='Y'):?>
        <center style="width: 100%;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:33px 0 12px 0;">
                <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                    <td width="100%" align="center">
                        <span style="font: 22px 'Proxima Nova', Arial, sans-serif;color: #212121;line-height:25px; text-transform: uppercase;display: block; -webkit-text-size-adjust:none;">
                            <?=GetMessage('SPOD_ORDER_BASKET').':'?>
                        </span>
                    </td>
                </tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:0;">
                <?
                foreach($arResult["BASKET"] as $prod):
                    ?>
                    <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                        <td width="100%">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 0; padding: 10px 0;">
                                <tr width="100%">
                                    <?
                                    
                                    $hasLink = !empty($prod["DETAIL_PAGE_URL"]);
                                    $actuallyHasProps = is_array($prod["PROPS"]) && !empty($prod["PROPS"]);
                                    $cnt = count($arParams["CUSTOM_SELECT_PROPS"]);
                                    
                                    foreach ($arParams["CUSTOM_SELECT_PROPS"] as $headerId):
                                        
                                        if($headerId == "NAME"):

                                            ?><td width="<?=70 / ($cnt - 2)?>%"><?
                                            
                                            if($hasLink):
                                                ?><a href="<?=$prod["DETAIL_PAGE_URL"]?>?utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_#MAILING_EVENT_ID#&MAILING_MESSAGE=#MAILING_MESSAGE#" target="_blank" class="orderList" style="text-decoration: none;"><?
                                            endif;
                                            ?><?=$prod["NAME"]?><?
                                            if($hasLink):
                                                ?></a><?
                                            endif;

                                            ?></td><?
                                            
                                        elseif($headerId == "PICTURE"):

                                            ?><td width="20%"><?
                                            
                                            if($hasLink):
                                                ?><a href="<?=$prod["DETAIL_PAGE_URL"]?>?utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_#MAILING_EVENT_ID#&MAILING_MESSAGE=#MAILING_MESSAGE#" target="_blank"><?
                                            endif;
                                            if($prod['PICTURE']['SRC']):
                                                ?><img src="<?=$prod['PICTURE']['SRC']?>" width="<?=$prod['PICTURE']['WIDTH']?>" height="<?=$prod['PICTURE']['HEIGHT']?>" alt="<?=$prod['NAME']?>" /><?
                                            endif;
                                            if($hasLink):
                                                ?></a><?
                                            endif;

                                            ?></td><?
                                            
                                        elseif($headerId == "PROPS" && $arResult['HAS_PROPS'] && $actuallyHasProps):
                                            
                                            ?>
                                            <td width="<?=70 / ($cnt - 2)?>%">
                                                <table cellspacing="0" class="bx_ol_sku_prop">
                                                    <?foreach($prod["PROPS"] as $prop):?>
                                                        <tr>
                                                            <td><nobr><?=htmlspecialcharsbx($prop["NAME"])?>:</nobr></td>
                                                            <td style="padding-left: 10px !important"><b><?=htmlspecialcharsbx($prop["VALUE"])?></b></td>
                                                        </tr>
                                                    <?endforeach?>
                                                </table>
                                            </td>
                                            <?

                                        elseif($headerId == "QUANTITY"):

                                            ?>
                                            <td width="10%" align="right">
                                                <span class="orderList">
                                                    <?=$prod["QUANTITY"]?>
                                                    <?if(strlen($prod['MEASURE_TEXT'])):?>
                                                        <?=$prod['MEASURE_TEXT']?>
                                                    <?else:?>
                                                        <?=GetMessage('SPOD_DEFAULT_MEASURE')?>
                                                    <?endif?>
                                                </span>
                                            </td>
                                            <?
                                            
                                        else:

                                            ?>
                                            <td width="<?=70 / ($cnt - 2)?>%" align="center">
                                                <span class="orderList">
                                                    <?=$prod[(strpos($headerId, 'PROPERTY_')===0 ? $headerId."_VALUE" : $headerId)]?>
                                                </span>
                                            </td>
                                            <?
                                        
                                        endif;
                                        
                                    endforeach;
                                    
                                    ?>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?
                    
                endforeach;
                ?>
            </table>
        </center>
    <?endif?>

    <?if($arParams["SHOW_ORDER_SUM"]=='Y'):?>
        <center>
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:11px 0;border-bottom: 1px solid #d9d9d9; border-top: 1px solid #d9d9d9;">
                <? ///// WEIGHT ?>
                <?if(floatval($arResult["ORDER_WEIGHT"])):?>
                    <tr width="100%" cellpadding="0" cellspacing="0" border="0" align="right">
                        <td width="80%" class="name"><?=GetMessage('SPOD_TOTAL_WEIGHT')?>:</td>
                        <td width="20%" class="value"><?=$arResult['ORDER_WEIGHT_FORMATED']?></td>
                    </tr>
                <?endif?>

                <? ///// PRICE SUM ?>
                <tr width="100%" cellpadding="0" cellspacing="0" border="0" align="right">
                    <td width="80%" class="name"><?=GetMessage('SPOD_PRODUCT_SUM')?>:</td>
                    <td width="20%" class="value"><?=$arResult['PRODUCT_SUM_FORMATED']?></td>
                </tr>

                <? ///// DELIVERY PRICE: print even equals 2 zero ?>
                <?if(strlen($arResult["PRICE_DELIVERY_FORMATED"])):?>
                    <tr width="100%" cellpadding="0" cellspacing="0" border="0" align="right">
                        <td width="80%" class="name"><?=GetMessage('SPOD_DELIVERY')?>:</td>
                        <td width="20%" class="value"><?=$arResult["PRICE_DELIVERY_FORMATED"]?></td>
                    </tr>
                <?endif?>

                <? ///// TAXES DETAIL ?>
                <?foreach($arResult["TAX_LIST"] as $tax):?>
                    <tr width="100%" cellpadding="0" cellspacing="0" border="0" align="right">
                        <td width="80%" class="name"><?=$tax["TAX_NAME"]?>:</td>
                        <td width="20%" class="value"><?=$tax["VALUE_MONEY_FORMATED"]?></td>
                    </tr>	
                <?endforeach?>

                <? ///// TAX SUM ?>
                <?if(floatval($arResult["TAX_VALUE"])):?>
                    <tr width="100%" cellpadding="0" cellspacing="0" border="0" align="right">
                        <td width="80%" class="name"><?=GetMessage('SPOD_TAX')?>:</td>
                        <td width="20%" class="value"><?=$arResult["TAX_VALUE_FORMATED"]?></td>
                    </tr>
                <?endif?>

                <? ///// DISCOUNT ?>
                <?if(floatval($arResult["DISCOUNT_VALUE"])):?>
                    <tr width="100%" cellpadding="0" cellspacing="0" border="0" align="right">
                        <td width="80%" class="name"><?=GetMessage('SPOD_DISCOUNT')?>:</td>
                        <td width="20%" class="value"><?=$arResult["DISCOUNT_VALUE_FORMATED"]?></td>
                    </tr>
                <?endif?>

                <tr width="100%" cellpadding="0" cellspacing="0" border="0" align="right">
                    <td width="80%" class="name fwb"><?=GetMessage('SPOD_SUMMARY')?>:</td>
                    <td width="20%" class="value fwb"><?=$arResult["PRICE_FORMATED"]?></td>
                </tr>
            </table>
        </center>
    <?endif?>
<?endif?>
