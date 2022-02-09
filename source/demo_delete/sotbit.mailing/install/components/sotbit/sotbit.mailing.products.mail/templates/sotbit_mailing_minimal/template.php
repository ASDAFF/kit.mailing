<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
use Bitrix\Main\Localization\Loc;
?>

<?
if(!empty($arResult['ITEMS']))
{
    ?>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:15px 36px 26px 36px;" bgcolor="#f5f5f5">
        <tr width="100%" cellpadding="0" cellspacing="0" border="0">
            <td width="100%" align="center">
                <?if($arParams['BLOCK_TITLE']):?>
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:18px 0 5px 0;">
                    <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                        <td width="100%" align="center">
                            <span style="font: 22px 'Proxima Nova', Arial, sans-serif;color: #212121;line-height:25px; text-transform: uppercase;display: block; -webkit-text-size-adjust:none;"><?=$arParams['BLOCK_TITLE']?></span>
                        </td>
                    </tr>
                </table>
                <?endif;?>
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:0;">
                    <?
                    foreach($arResult['ITEMS'] as $item)
                    {
                        ?>
                        <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                            <td width="100%">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 0; padding: 10px 0;">
                                    <tr width="100%">
                                        <td width="18%" align="left">
                                            <img src="<?=$item['PREVIEW_PICTURE']['src']?>" alt="" width="71" height="90" border="0">
                                        </td>
                                        <td width="62%" align="left">
                                            <a href="<?=$item['DETAIL_PAGE_URL']?>?utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_#MAILING_EVENT_ID#&MAILING_MESSAGE=#MAILING_MESSAGE#"
                                                target="_blank"
                                                style="font: 13px 'Proxima Nova', Arial, sans-serif;color: #212121;line-height:16px;display: block; -webkit-text-size-adjust:none; text-decoration: none;">
                                                <?=$item['NAME']?>
                                            </a>
                                        </td>
                                        <td width="20%" align="right">
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:10px 0;">
                                                <tr align="right">
                                                    <td>
                                                        <span style="font: 15px 'Proxima Nova', Arial, sans-serif;font-weight:bold; color: #212121;line-height:16px;display: block; -webkit-text-size-adjust:none;">
                                                            <?=$item['MIN_PRICE']['PRINT_DISCOUNT_VALUE']?>
                                                        </span>
                                                        <?if($item['MIN_PRICE']['VALUE'] != $item['MIN_PRICE']['DISCOUNT_VALUE']):?>
                                                        <span style="font: 13px 'Proxima Nova', Arial, sans-serif;font-weight:bold; color: #212121;line-height:16px;display: block; -webkit-text-size-adjust:none;">
                                                            <?=$item['MIN_PRICE']['PRINT_VALUE']?>
                                                        </span>
                                                        <?endif;?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:10px 0;">
                                                <tr bgcolor="#000000" style="height:28px;">
                                                    <td>
                                                        <a href="<?=$item['DETAIL_PAGE_URL']?>?utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_#MAILING_EVENT_ID#&MAILING_MESSAGE=#MAILING_MESSAGE#" style="font: 13px 'Proxima Nova', Arial, sans-serif;color: #ffffff;text-align: center;text-transform:uppercase;text-decoration:none;line-height:16px;display: block; -webkit-text-size-adjust:none;">
                                                            <?=Loc::getMessage('CATALOG_BUY_PRODUCTS_MAIL_BASKET')?>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?
                    }
                    ?>
                </table>
            </td>
        </tr>
    </table>

    <?
}
?>