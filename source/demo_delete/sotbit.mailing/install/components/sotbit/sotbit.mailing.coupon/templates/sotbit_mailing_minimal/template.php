<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

?>

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:30px 0 0 0;">
    <tr width="100%" cellpadding="0" cellspacing="0" border="0">
        <td width="100%" align="center">
            <span style="font: 19px 'Proxima Nova', Arial, sans-serif;color: #212121;line-height:28px;padding: 0 0 7px 0;text-transform: uppercase;display: block; -webkit-text-size-adjust:none;">
                <?=$arParams["PARAM_TITLE"]?>
            </span>
            <span style="font: 16px 'Proxima Nova', Arial, sans-serif;color: #d24a43;font-weight:bold;line-height:25px; -webkit-text-size-adjust:none;">
                купон на скидку <?=$arParams["PARAM_PERCENTS"]?>%
            </span>
            <span style="font: 16px 'Proxima Nova', Arial, sans-serif;color: #212121;line-height:25px; -webkit-text-size-adjust:none;">в ваших руках</span>
        </td>
    </tr>
    <tr width="100%" cellpadding="0" cellspacing="0" border="0">
        <td width="100%" align="center">
            <span style="font: 13px 'Proxima Nova', Arial, sans-serif;color: #616161;line-height:19px;padding: 11px 0 15px 0;display: block; -webkit-text-size-adjust:none;">
                —пасибо за регистрацию на нашем сайте. ” нас дл€ вас отличные новости!<br/>
                ¬ течение <?=$arParams["PARAM_DAYS"]?> дней вы можете приобрести любой товар в нашем магазине с <?=$arParams["PARAM_PERCENTS"]?>% скидкой.<br/>
                —пешите! —рок действи€ купона ограничен!
            </span>
        </td>
    </tr>
    <tr width="100%" cellpadding="0" cellspacing="0" border="0">
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:16px 24px 31px 24px;">
                <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                    <td width="100%" align="center">
                        <span style="font: 16px 'Proxima Nova', Arial, sans-serif;color: #212121;line-height:38px;border: 1px solid #d1d4d6;display: block;margin: 0 0 20px 0;max-width:271px; width: 100%; text-align: center; -webkit-text-size-adjust:none;">
                            #COUPON#
                        </span>
                    </td>
                </tr>
                <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                    <td width="100%" align="center">
                        <a href="<?=$arResult['SITE_URL']?>?utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_#MAILING_EVENT_ID#&MAILING_MESSAGE=#MAILING_MESSAGE#" target="_blank" style="font: 13px 'Proxima Nova', Arial, sans-serif;color: #ffffff;font-weight: bold;text-transform:uppercase;text-decoration: none;line-height:40px;display: block;max-width:271px; width: 100%; text-align: center;background-color: #000000; -webkit-text-size-adjust:none;">
                            <?=\Bitrix\Main\Localization\Loc::getMessage("BTN_NAME")?>
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
