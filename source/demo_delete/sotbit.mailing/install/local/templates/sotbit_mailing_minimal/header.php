<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $serverName;
$protocol_define = isset($_SERVER['HTTPS']) ? 'https' : 'http';
$protocol = \Bitrix\Main\Config\Option::get("main", "mail_link_protocol", $protocol_define, $arParams["SITE_ID"]);
if(empty($protocol))
	$protocol = $protocol_define;
$serverName = $protocol . "://" . $arParams["SERVER_NAME"];



global $moduleID;
global $phones;
global $email;

if(\Bitrix\Main\Loader::includeModule('sotbit.missshop'))
    $moduleID = 'sotbit.missshop';
if(\Bitrix\Main\Loader::includeModule('sotbit.mistershop'))
    $moduleID = 'sotbit.mistershop';
if(\Bitrix\Main\Loader::includeModule('sotbit.b2bshop'))
    $moduleID = 'sotbit.b2bshop';

if($moduleID)
{
    $phones = unserialize(\Bitrix\Main\Config\Option::get($moduleID, "MICRO_ORGANIZATION_PHONE", ""));
    $logo = \Bitrix\Main\Config\Option::get($moduleID, "LOGO", "");
    $email = \Bitrix\Main\Config\Option::get($moduleID, "EMAIL", "");
}
?>

<!DOCTYPE html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Proxima+Nova" rel="stylesheet">
</head>
<body style="background-color: #f7f8fa;">
<table width="700" align="center" cellpadding="0" cellspacing="0" border="0" style="margin:auto; padding:0; border: 1px solid #f1f1f1;" bgcolor="#ffffff">
    <tr>
        <td>
            <center style="width: 100%;">
                <!--[if gte mso 9]>
                <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0">
                    <tr>
                        <td>
                <![endif]-->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:31px 35px 29px 35px;">
                    <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                        <td width="35%" align="left" cellpadding="0" cellspacing="0" border="0">
                            <!--[if gte mso 9]>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                                <tr cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <td width="20%" align="left" style="vertical-align: top; padding-top: 4px;">
                            <![endif]-->
                            <!--[if !mso]><!-- -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:0;">
                                <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <td width="10%" align="left" style="vertical-align: top; padding-top: 4px;">
                                        <!--<![endif]-->
                                        <img src="<?=$serverName?>/local/templates/sotbit_mailing_minimal/img/mail_phone.png" alt="" border="0" width="11" height="11" style="display:block;text-decoration:none;outline:none;">
                                        <!--[if !mso]><!-- -->
                                    </td>
                                    <td width="90%" align="left">
                                        <!--<![endif]-->

                                        <!--[if gte mso 9]>
                                        </td>
                                        <td width="80%" align="right" cellpadding="0" cellspacing="0" border="0">
                                            <!--<![endif]-->
                                        <?
                                        if(!$phones)
                                            $phones = explode("\n", file_get_contents($serverName.'/local/templates/sotbit_mailing_minimal/phones.php'));
                                        
                                        foreach($phones as $phone)
                                        {
                                        ?>
                                            <!--[if gte mso 9]>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                                                <tr cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <td width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <![endif]-->
                                                        <a href="tel:<?=$phone?>" value="+<?=$phone?>" target="_blank" style="font: 16px 'Proxima Nova', Arial, sans-serif;color: #000000;font-weight: bold;letter-spacing:.25px;text-decoration: none;line-height:21px;display: block; -webkit-text-size-adjust:none;">
                                                            <font color="#000000" size="3" face="Proxima Nova, Arial, sans-serif">
                                                                <?=$phone?>
                                                            </font>
                                                        </a>
                                                        <!--[if gte mso 9]>
                                                    </td>
                                                </tr>
                                            </table>
                                            <![endif]-->
                                        <?
                                        }
                                        ?>
                                        <!--[if !mso]><!-- -->
                                    </td>
                                </tr>
                            </table>
                            <!--<![endif]-->
                            <!--[if gte mso 9]>
                            </td>
                            </tr>
                            </table>
                            <![endif]-->
                        </td>
                        <td width="35%" align="center">
                            <!--[if gte mso 9]>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                                <tr >
                                    <td width="100%">
                                        <![endif]-->
                                        <a href="<?=$serverName?>" target="_blank" style="color: #000000;text-decoration: none;display: block; -webkit-text-size-adjust:none;">
                                            <img src="<?if($logo) echo $logo; else CMain::IncludeFile('/local/templates/sotbit_mailing_minimal/logo.php');?>" alt="" border="0" width="195" style="display:block;text-decoration:none; outline:none;">
                                        </a>
                                        <!--[if gte mso 9]>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
                        </td>
                        <td width="30%" align="right" cellpadding="0" cellspacing="0" border="0">
                            <!--[if gte mso 9]>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                                <tr cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <td width="20%" align="left" style="vertical-align: top; padding-top: 6px;">
                            <![endif]-->
                            <!--[if !mso]><!-- -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:0;">
                                <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <td width="20%" align="right" style="vertical-align: top; padding-top: 6px;">
                                        <!--<![endif]-->
                                        <img src="<?=$serverName?>/local/templates/sotbit_mailing_minimal/img/mail.png" alt="" border="0" width="13" height="10" style="display:block;text-decoration:none; outline:none;">
                                        <!--[if !mso]><!-- -->
                                    </td>
                                    <td width="80%" align="left">
                                        <!--<![endif]-->
                                        <!--[if gte mso 9]>
                                     </td>
                                     <td width="80%" align="left" cellpadding="0" cellspacing="0" border="0">
                                         <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                                             <tr cellpadding="0" cellspacing="0" border="0" width="100%">
                                                 <td width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <![endif]-->
                                        <a href="mailto:<?if($email) echo $email; else CMain::IncludeFile('/local/templates/sotbit_mailing_minimal/email.php');?>" target="_blank" style="font: 16px 'Proxima Nova', Arial, sans-serif;color: #000000;font-weight: bold;text-decoration: none;line-height:21px;display: block;padding: 0 0 0 9px; -webkit-text-size-adjust:none;">
                                            <font color="#000000" size="3" face="Proxima Nova, Arial, sans-serif">
                                                <?if($email) echo $email; else CMain::IncludeFile('/local/templates/sotbit_mailing_minimal/email.php');?>
                                            </font>
                                        </a>
                                        <!--[if !mso]><!-- -->
                                    </td>
                                </tr>
                            </table>

                            <!--<![endif]-->
                            <!--[if gte mso 9]>
                            </td>
                            </tr>
                            </table>
                            </td>
                            </tr>
                            </table>
                            <![endif]-->
                        </td>
                    </tr>
                </table>
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding: 6px 0; border-bottom: 1px solid #d9d9d9; border-top: 1px solid #d9d9d9;">
                    <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                        <td width="100%" align="center">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:0">
                                <tr>
                                    <?
                                    EventMessageThemeCompiler::includeComponent(
                                        "bitrix:menu",
                                        "top",
                                        array(
                                            "ALLOW_MULTI_SELECT" => "N",
                                            "DELAY" => "N",
                                            "MAX_LEVEL" => "1",
                                            "MENU_CACHE_GET_VARS" => array(""),
                                            "MENU_CACHE_TIME" => "3600",
                                            "MENU_CACHE_TYPE" => "N",
                                            "MENU_CACHE_USE_GROUPS" => "Y",
                                            "ROOT_MENU_TYPE" => "top",
                                            "USE_EXT" => "N",
                                        ),
                                        false
                                    );
                                    ?>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
    <tr>
        <td>
            <center style="width: 100%;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:33px 35px 5px 35px; font-family:'Proxima Nova', Arial, sans-serif; color:#616161;">
                    <tr width="100%" cellpadding="0" cellspacing="0" border="0">
                        <td width="100%" align="center">