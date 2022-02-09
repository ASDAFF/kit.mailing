<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
use Bitrix\Main\Localization\Loc;
$skuTemplate = array();
?> 

 
<? if(!empty($arResult['ITEMS'])):?>

    <div style="background-color: #fff;padding-bottom:45px;">
        <?if($arParams['BLOCK_TITLE']):?>
        <h2 style="text-align:center;font-weight:normal;margin:0;color:#55595c;font-size:16px;margin-bottom:20px"><?=$arParams['BLOCK_TITLE']?></h2>
        <?endif;?>
        <?
        $all_count = count($arResult['ITEMS']);
        $count_now = 1;
        foreach($arResult['ITEMS'] as $item): ?>    
        <table width="100%" height="130px" cellpadding="0" cellspacing="0" style="border: 1px solid #d7dde1;<?if($all_count>$count_now):?>border-bottom: none;<?endif;?>padding: 0 20px;">
                <tbody>
                    <tr>                            
                        <td valign="center" align="center" width="140px"  style="padding-top: 10px;">
                            <a style="display: block;" href="<?=$item['DETAIL_PAGE_URL']?>?utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_#MAILING_EVENT_ID#&MAILING_MESSAGE=#MAILING_MESSAGE#"><img src="<?=$item['PREVIEW_PICTURE']['src']?>" width="138"></a>
                        </td>
                                
                        <td valign="top" width="305px" style="padding: 35px 30px 0 30px;text-align:left">
                            <a href="<?=$item['DETAIL_PAGE_URL']?>?utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_#MAILING_EVENT_ID#&MAILING_MESSAGE=#MAILING_MESSAGE#" style="color:#546f83;font-size:14px"><?=$item['NAME']?></a>    
                        </td>
                                
                        <td valign="middle" style="text-align:center;padding-top: 15px;">
                        
                            <p style="color:#55595c;font-size:24px;margin: 0;">
                                <?=$item['MIN_PRICE']['PRINT_DISCOUNT_VALUE']?>
                            </p>
                            
                            <? if($item['MIN_PRICE']['VALUE'] != $item['MIN_PRICE']['DISCOUNT_VALUE']): ?>
                            <p style="font-size:24px; color: #adadad; text-decoration: line-through; font-size: 24px;">
                                <?=$item['MIN_PRICE']['PRINT_VALUE']?>
                            </p>
                            <?endif; ?>                    
                        
                            <a href="<?=$item['DETAIL_PAGE_URL']?>?utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_#MAILING_EVENT_ID#&MAILING_MESSAGE=#MAILING_MESSAGE#" style="margin-top: 20px;display: inline-block;text-decoration:none;background:#b2091b;font-size:16px;color:#fff;padding: 8px 28px">
                            <?=Loc::getMessage('CATALOG_BUY_PRODUCTS_MAIL_BASKET')?>
                            </a>
                        </td>                               
                    </tr>
                </tbody>
        </table>    
        <?
        $count_now++;
        endforeach;?>       
    </div>
<? endif ?>