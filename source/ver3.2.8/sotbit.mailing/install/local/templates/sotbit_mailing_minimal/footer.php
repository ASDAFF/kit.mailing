<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $serverName;
global $moduleID;
global $phones;
global $email;

$vk = \Bitrix\Main\Config\Option::get("sotbit.missshop", "LINK_VK", "");
$fb = \Bitrix\Main\Config\Option::get("sotbit.missshop", "LINK_FB", "");
$tw = \Bitrix\Main\Config\Option::get("sotbit.missshop", "LINK_TW", "");
$gl = \Bitrix\Main\Config\Option::get("sotbit.missshop", "LINK_GL", "");
?>
                    </td>
                </tr>
            </table>
        </center>
    </td>
</tr>
<tr>
    <td>
        <center style="width: 100%;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f5f5f5" style="margin:0; padding:13px 36px 25px 36px;">
                <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                    <td width="100%">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:0">
                            <tr>
                                <?
                                EventMessageThemeCompiler::includeComponent(
                                    "bitrix:menu",
                                    "bottom",
                                    array(
                                        "ALLOW_MULTI_SELECT" => "N",
                                        "DELAY" => "N",
                                        "MAX_LEVEL" => "2",
                                        "MENU_CACHE_GET_VARS" => array(""),
                                        "MENU_CACHE_TIME" => "3600",
                                        "MENU_CACHE_TYPE" => "N",
                                        "MENU_CACHE_USE_GROUPS" => "Y",
                                        "ROOT_MENU_TYPE" => "bottom",
                                        "CHILD_MENU_TYPE" => "bottom_inner",
                                        "USE_EXT" => "Y"
                                    ),
                                    false
                                );
                                ?>
                                <td width="28%" style="vertical-align: top;">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:0">
                                        <tr>
                                            <td width="100%">
                                                <!--[if gte mso 9]>
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                                                    <tr >
                                                        <td width="100%">
                                                            <![endif]-->
                                                            <span style="font: 15px 'Proxima Nova', Arial, sans-serif;color: #000000;font-weight: bold;text-transform:uppercase;text-decoration: none;line-height:51px;display: block; -webkit-text-size-adjust:none;"><font color="#000000" size="3" face="Proxima Nova, Arial, sans-serif">Контакты</font></span>
                                                            <!--[if gte mso 9]>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <![endif]-->
                                                <!--[if gte mso 9]>
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                                                    <tr >
                                                        <td width="100%">
                                                            <![endif]-->
                                                            <a href="mailto:<?if($email) echo $email; else CMain::IncludeFile('/local/templates/sotbit_mailing_minimal/email.php');?>" target="_blank" style="font: 16px 'Proxima Nova', Arial, sans-serif;color: #212121;text-decoration: none;line-height:30px;padding-bottom: 13px;display: block; -webkit-text-size-adjust:none;">
                                                                <font color="#212121" size="3" face="Proxima Nova, Arial, sans-serif">
                                                                    <?if($email) echo $email; else CMain::IncludeFile('/local/templates/sotbit_mailing_minimal/email.php');?>
                                                                </font>
                                                            </a>
                                                            <!--[if gte mso 9]>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <![endif]-->
                                                <?
                                                foreach($phones as $phone)
                                                {
                                                ?>
                                                <!--[if gte mso 9]>
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0;">
                                                    <tr >
                                                        <td width="100%">
                                                            <![endif]-->
                                                            <a href="tel:<?=$phone?>" value="+<?=$phone?>" target="_blank" style="font: 16px 'Proxima Nova', Arial, sans-serif;color: #000000;text-decoration: none;line-height:25px;display: block; -webkit-text-size-adjust:none;"><font color="#000000" size="3" face="Proxima Nova, Arial, sans-serif"><?=$phone?></font></a>
                                                            <!--[if gte mso 9]>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <![endif]-->
                                                <?
                                                }
                                                ?>
                                                <div style="padding-top: 26px;">
                                                    <a href="<?=$vk?>" target="_blank" style="font-weight: normal;text-decoration: none;cursor: pointer;">
                                                        <font color="#000000" face="Arial" size="2">
                                                            <img src="<?=$serverName?>/local/templates/sotbit_mailing_minimal/img/mail_vk.png" alt="vk" width="30" height="31" style="border: 0; outline:none; text-decoration:none;">
                                                        </font>
                                                    </a> &nbsp;
                                                    <a href="<?=$fb?>" target="_blank" style="font-weight: normal;text-decoration: none;cursor: pointer;">
                                                        <font color="#000000" face="Arial" size="2">
                                                            <img src="<?=$serverName?>/local/templates/sotbit_mailing_minimal/img/mail_fb.png" alt="facebook" width="30" height="31" style="border: 0; outline:none; text-decoration:none;">
                                                        </font>
                                                    </a> &nbsp;
                                                    <a href="<?=$tw?>" target="_blank" style="font-weight: normal;text-decoration: none;cursor: pointer;">
                                                        <font color="#000000" face="Arial" size="2">
                                                            <img src="<?=$serverName?>/local/templates/sotbit_mailing_minimal/img/mail_tw.png" alt="twitter" width="30" height="31" style="border: 0; outline:none; text-decoration:none;">
                                                        </font>
                                                    </a> &nbsp;
                                                    <a href="<?=$gl?>" target="_blank" style="font-weight: normal;text-decoration: none;cursor: pointer;">
                                                        <font color="#000000" face="Arial" size="2">
                                                            <img src="<?=$serverName?>/local/templates/sotbit_mailing_minimal/img/mail_gl.png" alt="google" width="30" height="31" style="border: 0; outline:none; text-decoration:none;">
                                                        </font>
                                                    </a> &nbsp;
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!--[if gte mso 9]>
            </td></tr></table>
            <![endif]-->
        </center>
    </td>
</tr>
<tr>
    <td>
        <center>
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding: 34px 100px 25px 100px;" bgcolor="#616161">
                <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                    <td width="100%" align="center">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:0 0 10px 0;">
                            <tr>
                                <td align="center">
                                    <!--[if gte mso 9]>
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                                        <tr>
                                            <td width="100%">
                                                <![endif]-->
                                                <span style="font: 12px 'Proxima Nova', Arial, sans-serif;color: #ffffff;line-height:1.2;display: block; -webkit-text-size-adjust:none;">
                                                    <font color="#ffffff" size="2" face="Proxima Nova, Arial, sans-serif">
                                                        <?=\Bitrix\Main\Localization\Loc::getMessage("NOTIFICATION_1");?>
                                                        <a href="<?=$serverName?>" target="_blank" style="font: 12px 'Proxima Nova', Arial, sans-serif;color: #ffffff;line-height:1.2; text-decoration-color: #898989; -webkit-text-size-adjust:none;">
                                                            <?=str_replace(array('http://', 'https://'), '', $serverName)?>
                                                        </a>
                                                    </font>
                                                </span>
                                                <span style="font: 12px 'Proxima Nova', Arial, sans-serif;color: #ffffff;line-height:1.2;display: block; -webkit-text-size-adjust:none;">
                                                    <font color="#ffffff" size="2" face="Proxima Nova, Arial, sans-serif">
                                                        <?=\Bitrix\Main\Localization\Loc::getMessage("NOTIFICATION_2");?>
                                                    </font>
                                                </span>
                                                <!--[if gte mso 9]>
                                            </td>
                                        </tr>
                                    </table>
                                    <![endif]-->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                    <td width="100%" align="center">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:0">
                            <tr>
                                <td align="center">
                                    <!--[if gte mso 9]>
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                                        <tr>
                                            <td width="100%">
                                            <![endif]-->
                                                <a href="<?=$serverName?>" target="_blank" style="font: 12px 'Proxima Nova', Arial, sans-serif;color: #ffffff;text-decoration-color: #898989;line-height:30px;display: block; -webkit-text-size-adjust:none;">
                                                    <font color="#ffffff" size="2" face="Proxima Nova, Arial, sans-serif">
                                                        <?=\Bitrix\Main\Localization\Loc::getMessage("GO_TO_THE_SITE");?>
                                                    </font>
                                                </a>
                                            <!--[if gte mso 9]>
                                            </td>
                                        </tr>
                                    </table>
                                    <![endif]-->
                                </td>
                                <?if($moduleID):?>
                                <td align="center">
                                    <!--[if gte mso 9]>
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                                        <tr>
                                            <td width="100%">
                                                <![endif]-->
                                                <a href="<?=$serverName.'/personal/subscribe/'?>" target="_blank" style="font: 12px 'Proxima Nova', Arial, sans-serif;color: #ffffff;text-decoration-color: #898989;line-height:30px;display: block; -webkit-text-size-adjust:none;">
                                                    <font color="#ffffff" size="2" face="Proxima Nova, Arial, sans-serif">
                                                        <?=\Bitrix\Main\Localization\Loc::getMessage("EDIT_SUBSCRIPTIONS");?>
                                                    </font>
                                                </a>
                                                <!--[if gte mso 9]>
                                            </td>
                                        </tr>
                                    </table>
                                    <![endif]-->
                                </td>
                                <?endif?>
                                <td align="center">
                                    <!--[if gte mso 9]>
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                                        <tr>
                                            <td width="100%">
                                                <![endif]-->
                                                <a href="<?=$serverName?>?utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_#MAILING_EVENT_ID#&MAILING_MESSAGE=#MAILING_MESSAGE#&MAILING_UNSUBSCRIBE=#MAILING_UNSUBSCRIBE#" target="_blank" style="font: 12px 'Proxima Nova', Arial, sans-serif;color: #ffffff;text-decoration-color: #898989;line-height:30px;display: block; -webkit-text-size-adjust:none;">
                                                    <font color="#ffffff" size="2" face="Proxima Nova, Arial, sans-serif">
                                                        <?=\Bitrix\Main\Localization\Loc::getMessage("CANCEL_SUBSCRIPTION");?>
                                                    </font>
                                                </a>
                                                <!--[if gte mso 9]>
                                            </td>
                                        </tr>
                                    </table>
                                    <![endif]-->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </td>
</tr>
</table>
</body>